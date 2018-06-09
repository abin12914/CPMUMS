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
        $destination    = '/images/accounts/'; // image file upload path
        $saveFlag       = 0;
        $fileName       = "";
        
        $accountName        = $request->get('account_name');
        $description        = $request->get('description');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $relation           = $request->get('relation_type');

        try {
            $openingBalanceAccount = Account::findOrFail(8);//where('account_name','Account Opening Balance')->first();
        } catch (Exception $e) {
            return [
                'flag'      => false,
                'errorCode' => config('settings.error_method_code.Save')."/01"
            ];
        }
        //$openingBalanceAccountId = $openingBalanceAccount->id;

        //upload image
        if ($request->hasFile('image_file')) {
            $file       = $request->file('image_file');
            $extension  = $file->getClientOriginalExtension(); // getting image extension
            $fileName   = $name.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
            $fileName   = $destination.$fileName;//file name for saving to db
        }

        try {
            $account = new Account;
            $account->account_name      = $accountName;
            $account->description       = $description;
            $account->type              = 3;
            $account->relation          = $relation;
            $account->financial_status  = $financialStatus;
            $account->opening_balance   = $openingBalance;
            $account->name              = $name;
            $account->phone             = $phone;
            $account->address           = $address;
            $account->image             = $fileName;
            $account->status            = 1;
            
            if($account->save()) {
                if($financialStatus == 1) { //incoming [account holder gives cash to company] [Creditor]
                    $debitAccountId     = 8; //$openingBalanceAccountId;//flow into the opening balance account
                    $creditAccountId    = $account->id; //flow out from new account
                    $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
                } else if($financialStatus == 2){ //outgoing [company gives cash to account holder] [Debitor]
                    $debitAccountId     = $account->id; //flow into new account
                    $creditAccountId    = 8; //$openingBalanceAccountId;//flow out from the opening balance account
                    $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
                } else {
                    $debitAccountId     = 8; //$openingBalanceAccountId;
                    $creditAccountId    = $account->id;
                    $particulars        = "Opening balance of ". $name . " - None";
                    $openingBalance     = 0;
                }

                $date = Carbon::now()->format('Y-m-d');
                
                $transaction = new Transaction;
                $transaction->debit_account_id  = $debitAccountId;
                $transaction->credit_account_id = $creditAccountId;
                $transaction->amount            = !empty($openingBalance) ? $openingBalance : '0';
                $transaction->transaction_date  = $date;
                $transaction->particulars       = $particulars;
                $transaction->status            = 1;
                $transaction->created_user_id   = Auth::user()->id;
                if($transaction->save()) {
                    return [
                        'flag'  => true,
                        'id'    => $account->id,
                    ];
                } else {
                    //delete the account if opening balance transaction saving failed
                    $account->forceDelete();

                    $saveFlag = "02";
                }
            } else {
                $saveFlag = "03";
            }
        } catch (Exception $e) {
            return [
                'flag'      => false,
                'errorCode' => config('settings.error_method_code.Save')."/04",
            ];
        }

        return [
                'flag'      => false,
                'errorCode' => config('settings.error_method_code.Save'). "/". $saveFlag,
            ];
    }

    /**
     * return account.
     */
    public function getAccount($id)
    {
        $account = Account::active()->where('id', $id)->first();

        if(empty($account) || empty($account->id)) {
            $account = [];
        }

        return $account;
    }

    public function deleteAccount($id, $forceFlag=false)
    {
        $errorCode = 0;
        $account = $this->getAccount($id);

        if(!empty($account) && !empty($account->id)) {
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
        } else {
            $errorCode = '03';
        }
        return [
            'flag'          => false,
            'error_code'    => config('settings.error_method_code.Delete')."/". $errorCode,
        ];
    }
}
