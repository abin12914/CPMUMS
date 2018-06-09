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
        $employees = Employee::with('account')->where('status', 1);

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
        $destination    = '/images/accounts/'; // image file upload path
        $saveFlag       = 0;
        $fileName       = "";
        
        $name               = $request->get('name');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $wageType           = $request->get('wage_type');
        $wage               = $request->get('wage');
        $accountName        = $request->get('account_name');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');

        $openingBalanceAccount = Account::where('account_name','Account Opening Balance')->first();
        if(!empty($openingBalanceAccount) && !empty($openingBalanceAccount->id)) {
            $openingBalanceAccountId = $openingBalanceAccount->id;
        } else {
            return [
                'flag'      => false,
                'errorCode' => "01"
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

        $account = new Account;
        $account->account_name      = $accountName;
        $account->description       = "Staff account of ".$name;
        $account->type              = 3;
        $account->relation          = 5;
        $account->financial_status  = $financialStatus;
        $account->opening_balance   = $openingBalance;
        $account->name              = $name;
        $account->phone             = $phone;
        $account->address           = $address;
        $account->image             = $fileName;
        $account->status            = 1;
        if($account->save()) {
            $employee = new Employee;
            $employee->wage_type    = $wageType;
            $employee->wage         = $wage;
            $employee->account_id   = $account->id;
            $employee->status       = 1;

            if($employee->save()){
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

                $dateTime = Carbon::now()->format('Y-m-d H:i:s');
                
                $transaction = new Transaction;
                $transaction->debit_account_id  = $debitAccountId;
                $transaction->credit_account_id = $creditAccountId;
                $transaction->amount            = !empty($openingBalance) ? $openingBalance : '0';
                $transaction->transaction_date  = $dateTime;
                $transaction->particulars       = $particulars;
                $transaction->status            = 1;
                $transaction->created_user_id   = Auth::user()->id;
                if($transaction->save()) {
                    return [
                        'flag'  => true,
                        'id'    => $employee->id,
                    ];
                } else {
                    //delete the account, account detail, employee if opening balance transaction saving failed
                    $account->forceDelete();
                    $employee->forceDelete();

                    $saveFlag = '01';
                }
            } else {
                //delete the account, account detail if employee saving failed
                $account->forceDelete();

                $saveFlag = '02';
            }
        } else {
            $saveFlag = '03';
        }
        
        return [
                'flag'      => false,
                'errorCode' => $saveFlag,
            ];
    }

    /**
     * return employee.
     */
    public function getEmployee($id)
    {
        $employee = Employee::with('account')->where('status', 1)->where('id', $id)->first();

        if(empty($employee) || empty($employee->id)) {
            $employee = [];
        }

        return $employee;
    }

    public function deleteEmployee($id, $forceFlag=false)
    {
        $errorCode = 'Unknown';
        $employee = Employee::with('account')->where('status', 1)->where('id', $id)->first();

        if(!empty($employee) && !empty($employee->id)) {
            if($forceFlag) {
                if($employee->account->forceDelete() && $employee->forceDelete()) {
                    return [
                        'flag'  => true,
                        'force' => true,
                    ];
                } else {
                    $errorCode = '04';
                }
            } else {
                if($employee->account->delete()) {
                    if($employee->delete()) {
                        return [
                            'flag'  => true,
                            'force' => false,
                        ];
                    } else {
                        $errorCode = '05';
                    }
                } else {
                    $errorCode = '06';
                }
            }
        } else {
            $errorCode = '07';
        }

        return [
            'flag'          => false,
            'error_code'    => $errorCode,
        ];
    }
}
