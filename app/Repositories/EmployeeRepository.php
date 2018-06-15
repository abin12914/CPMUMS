<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Account;
use App\Models\Transaction;
use \Carbon\Carbon;
use Auth;

class EmployeeRepository
{
    /**
     * Return accounts.
     */
    public function getEmployees($params=[], $noOfRecords=null)
    {
        $employees = Employee::with('account')->active();

        foreach ($params as $key => $value) {
            if(!empty($value)) {
                $employees = $employees->where($key, $value);
            }
        }
        if(!empty($noOfRecords)) {
            if($noOfRecords == 1) {
                $employees = $employees->first();
            } else {
                $employees = $employees->paginate($noOfRecords);
            }
        } else {
            $employees= $employees->get();
        }
        if(empty($employees) || $employees->count() < 1) {
            $employees = [];
        }

        return $employees;
    }

    /**
     * Action for saving accounts.
     */
    public function saveEmployee($request)
    {
        $openingBalanceAccountId = config('constants.accountConstants.AccountOpeningBalance.id');
        $saveFlag = 0;

        $destination    = '/images/accounts/'; // image file upload path
        $fileName       = "";
        
        $name               = $request->get('name');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');

        try {
            $openingBalanceAccount = Account::findOrFail($openingBalanceAccountId);
        } catch (Exception $e) {
            return [
                'flag'      => false,
                'errorCode' => config('settings.error_method_code.Save')."/01"
            ];
        }

        //upload image
        if ($request->hasFile('image_file')) {
            $file       = $request->file('image_file');
            $extension  = $file->getClientOriginalExtension(); // getting image extension
            $fileName   = "emp_".$name.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
            $fileName   = $destination.$fileName;//file name for saving to db
        }

        $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        //wrappin db transactions
        DB::beginTransaction();

        try {
            //account saving
            $account = new Account;
            $account->account_name      = $request->get('account_name');
            $account->description       = "Staff account of ".$name;
            $account->type              = 3;
            $account->relation          = 5;
            $account->financial_status  = $financialStatus;
            $account->opening_balance   = $openingBalance;
            $account->name              = $name;
            $account->phone             = $request->get('phone');
            $account->address           = $request->get('address');
            $account->image             = $fileName;
            $account->status            = 1;
            //account save
            $account->save();

            //employee saving
            $employee = new Employee;
            $employee->wage_type    = $request->get('wage_type');
            $employee->wage         = $request->get('wage');
            $employee->account_id   = $account->id;
            $employee->status       = 1;
            //employee save
            $employee->save();

            //opening balance transaction details
            if($financialStatus == 1) {//incoming [account holder gives cash to company] [Creditor]
                $debitAccountId     = $openingBalanceAccountId;//flow into the opening balance account
                $creditAccountId    = $account->id;//flow out from new account
                $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
            } else if($financialStatus == 2){//outgoing [company gives cash to account holder] [Debitor]
                $debitAccountId     = $account->id;//flow into new account
                $creditAccountId    = $openingBalanceAccountId;//flow out from the opening balance account
                $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
            } else {
                $debitAccountId     = $openingBalanceAccountId;
                $creditAccountId    = $account->id;
                $particulars        = "Opening balance of ". $name . " - None";
                $openingBalance     = 0;
            }

            //transaction saving
            $transaction = new Transaction;
            $transaction->debit_account_id  = $debitAccountId;
            $transaction->credit_account_id = $creditAccountId;
            $transaction->amount            = !empty($openingBalance) ? $openingBalance : '0';
            $transaction->transaction_date  = $dateTime;
            $transaction->particulars       = $particulars;
            $transaction->status            = 1;
            $transaction->created_user_id   = Auth::user()->id;
            //transaction save
            $transaction->save();

            DB::commit();

            $saveFlag = true;
        } catch (Exception $e) {
             DB::rollback();
        }
        
        if($saveFlag) {
            return [
                'flag'  => true,
                'id'    => $account->id,
            ];
        }

        return [
            'flag'      => false,
            'errorCode' => config('settings.error_method_code.Save')."/02"
        ];
    }

    /**
     * return employee.
     */
    public function getEmployee($id)
    {
        $employee = Employee::with('account')->active()->findOrFail($id);

        if(empty($employee) || empty($employee->id)) {
            $employee = [];
        }

        return $employee;
    }

    public function deleteEmployee($id, $forceFlag=false)
    {
        $errorCode = 0;
        $employee = $this->getEmployee($id);

        if($forceFlag) {
            if($employee->account->forceDelete() && $employee->forceDelete()) {
                return [
                    'flag'  => true,
                    'force' => true,
                ];
            } else {
                $errorCode = '01';
            }
        } else {
            if($employee->account->delete()) {
                if($employee->delete()) {
                    return [
                        'flag'  => true,
                        'force' => false,
                    ];
                } else {
                    $errorCode = '02';
                }
            } else {
                $errorCode = '03';
            }
        }

        return [
            'flag'          => false,
            'error_code'    => config('settings.error_method_code.Delete')."/". $errorCode,
        ];
    }
}
