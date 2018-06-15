<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transaction;
use \Carbon\Carbon;
use Auth;
use Exception;

class AccountRepository
{
    /**
     * Return accounts.
     */
    public function getAccounts($params=[], $noOfRecords=null, $typeFlag=true)
    {
        $accounts = Account::active();
        if($typeFlag) {
            $accounts = $accounts->where('type', 3);
        }

        foreach ($params as $key => $value) {
            if(!empty($value)) {
                $accounts = $accounts->where($key, $value);
            }
        }
        if(!empty($noOfRecords)) {
            if($noOfRecords == 1) {
                $accounts = $accounts->first();
            } else {
                $accounts = $accounts->paginate($noOfRecords);
            }
        } else {
            $accounts= $accounts->get();
        }
        if(empty($accounts) || $accounts->count() < 1) {
            $accounts = [];
        }

        return $accounts;
    }

    /**
     * Action for saving accounts.
     */
    public function saveAccount($request)
    {
        $openingBalanceAccountId = config('constants.accountConstants.AccountOpeningBalance.id');
        $saveFlag = false;

        $destination    = '/images/accounts/'; // image file upload path
        $fileName       = "";
        
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

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
            $fileName   = $name.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
            $fileName   = $destination.$fileName;//file name for saving to db
        }

        $date = Carbon::now()->format('Y-m-d');

        //wrappin db transactions
        DB::beginTransaction();

        try {
            //account saving
            $account = new Account;
            $account->account_name      = $request->get('account_name');
            $account->description       = $request->get('description');
            $account->type              = 3;
            $account->relation          = $request->get('relation_type');
            $account->financial_status  = $financialStatus;
            $account->opening_balance   = $openingBalance;
            $account->name              = $name;
            $account->phone             = $request->get('phone');
            $account->address           = $request->get('address');
            $account->image             = $fileName;
            $account->status            = 1;
            //account save
            $account->save();

            //opening balance transaction details
            if($financialStatus == 1) { //incoming [account holder gives cash to company] [Creditor]
                $debitAccountId     = $openingBalanceAccountId; //cash flow into the opening balance account
                $creditAccountId    = $account->id; //flow out from new account
                $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
            } else if($financialStatus == 2){ //outgoing [company gives cash to account holder] [Debitor]
                $debitAccountId     = $account->id; //flow into new account
                $creditAccountId    = $openingBalanceAccountId; //flow out from the opening balance account
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
            $transaction->amount            = $openingBalance;
            $transaction->transaction_date  = $date;
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
     * return account.
     */
    public function getAccount($id)
    {
        $account = Account::active()->findOrFail($id);

        if(empty($account) || empty($account->id)) {
            $account = [];
        }

        return $account;
    }

    public function deleteAccount($id, $forceFlag=false)
    {
        $errorCode = 0;
        $account = $this->getAccount($id);

        if($forceFlag) {
            if($account->forceDelete()) {
                return [
                    'flag'  => true,
                    'force' => true,
                ];
            } else {
                $errorCode = '01';
            }
        } else {
            if($account->delete()) {
                return [
                    'flag'  => true,
                    'force' => false,
                ];
            } else {
                $errorCode = '02';
            }
        }

        return [
            'flag'          => false,
            'error_code'    => config('settings.error_method_code.Delete')."/". $errorCode,
        ];
    }
}
