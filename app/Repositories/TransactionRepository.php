<?php

namespace App\Repositories;

use App\Models\Transaction;
use Auth;
use Exception;
use App\Exceptions\AppCustomException;

class TransactionRepository
{
    public $repositoryCode, $errorCode = 0, $transactionRelations=[];

    public function __construct()
    {
        $this->repositoryCode       = config('settings.repository_code.TransactionRepository');
        $this->transactionRelations = config('constants.transactionRelations');
    }
    /**
     * Return transactions.
     */
    public function getTransactions($params=[], $orParams=[], $relation=null, $noOfRecords=null)
    {
        $transactions = [];
        try {
            $transactions = Transaction::active();

            //where parameters
            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $transactions = $transactions->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            //orwhere parameters
            $transactions = $transactions->where(function ($qry) use($orParams) {
                foreach ($orParams as $key => $param) {
                    if(!empty($param) && !empty($param['paramValue'])) {
                        $qry = $qry->orWhere($param['paramName'], $param['paramOperator'], $param['paramValue']);
                    }
                }
            });

            //has relation checking
            if(!empty($relation)) {
                $transactions = $transactions->has($this->transactionRelations[$relation]['relationName']);
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $transactions = $transactions->paginate($noOfRecords);
            } else {
                $transactions= $transactions->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $transactions;
    }

    /**
     * Action for saving transaction.
     */
    public function saveTransaction($inputArray, $transaction=null)
    {
        $saveFlag = false;

        try {
            //transaction saving
            if(empty($transaction)) {
                $transaction = new Transaction;
            }
            $transaction->debit_account_id  = $inputArray['debit_account_id'];
            $transaction->credit_account_id = $inputArray['credit_account_id'];
            $transaction->amount            = $inputArray['amount'];
            $transaction->transaction_date  = $inputArray['transaction_date'];
            $transaction->particulars       = $inputArray['particulars'];
            $transaction->status            = 1;
            $transaction->branch_id         = $inputArray['branch_id'];
            $transaction->created_user_id   = Auth::user()->id;
            //transaction save
            $transaction->save();
            
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
                'id'    => $transaction->id,
            ];
        }
        
        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * return account.
     */
    public function getTransaction($id)
    {
        $transaction = [];

        try {
            $transaction = Transaction::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $transaction;
    }

    public function deleteTransaction($id, $forceFlag=false)
    {
        $deleteFlag = false;

       try {
            //get transaction
            $transaction = $this->getTransaction($id);

            if($forceFlag) {
                $transaction->forceDelete();
            } else {
                $transaction->delete();
            }

            $deleteFlag = true;
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 5;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        if($deleteFlag) {
            return [
                'flag'  => true,
                'force' => $forceFlag,
            ];
        }

        return [
            'flag'          => false,
            'error_code'    => $repositoryCode."/D/04",
        ];
    }
}
