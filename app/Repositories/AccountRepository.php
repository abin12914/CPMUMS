<?php

namespace App\Repositories;

use App\Models\Account;
use Exception;
use App\Exceptions\AppCustomException;

class AccountRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.AccountRepository');
    }

    /**
     * Return accounts.
     */
    public function getAccounts($params=[], $noOfRecords=null, $typeFlag=true, $activeFlag=true)
    {
        $accounts = [];

        try {
            $accounts = Account::query();

            if($activeFlag) {
                $accounts = $accounts->active(); //status == 1
            }

            if($typeFlag) {
                $accounts = $accounts->where('type', 3); //personal accounts
            }

            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $accounts = $accounts->where($key, $value);
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $accounts = $accounts->paginate($noOfRecords);
            } else {
                $accounts= $accounts->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $accounts;
    }

    /**
     * Action for saving accounts.
     */
    public function saveAccount($inputArray, $account=null)
    {
        $saveFlag   = false;

        try {
            //account saving
            if(empty($account)) {
                $account = new Account;
            }
            $account->account_name      = $inputArray['account_name'];
            $account->description       = $inputArray['description'];
            $account->type              = 3; //type = personal account
            $account->relation          = $inputArray['relation'];
            $account->financial_status  = $inputArray['financial_status'];
            $account->opening_balance   = $inputArray['opening_balance'];
            $account->name              = $inputArray['name'];
            $account->phone             = $inputArray['phone'];
            $account->address           = $inputArray['address'];
            $account->image             = $inputArray['image'];
            $account->gstin             = !empty($inputArray['gstin']) ? $inputArray['gstin'] : null;
            $account->status            = $inputArray['status'];
            //account save
            $account->save();

            $saveFlag = true;
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 2;
            }

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        if($saveFlag) {
            return [
                'flag'  => true,
                'id'    => $account->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * return account.
     */
    public function getAccount($id, $activeFlag=true)
    {
        $account = [];

        try {
            $account = Account::query();
            
            if($activeFlag) {
                $account = $account->active();
            }

            $account = $account->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $account;
    }

    public function deleteAccount($id, $forceFlag=false)
    {
        return [
            'flag'          => false,
            'errorCode'    => $this->repositoryCode + 6,
        ];
    }
}
