<?php

namespace App\Repositories;

use App\Models\Transaction;
use \Carbon\Carbon;
use Auth;
use Exception;
use App\Exceptions\AppCustomException;
use DB;

class TransactionRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.TransactionRepository');
    }
    /**
     * Return transactions.
     */
    public function getTransactions($params=[], $noOfRecords=null)
    {
        try {
            $transactions = Transaction::active();

            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $transactions = $transactions->where($key, $value);
                }
            }
            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $transactions = $transactions->paginate($noOfRecords);
            } else {
                $transactions= $transactions->get();
            }
            if(empty($transactions) || $transactions->count() < 1) {
                $transactions = [];
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
    public function saveTransaction($inputArray)
    {
        $saveFlag = false;

        try {
            //transaction saving
            $transaction = new Transaction;
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
            throw new AppCustomException("CustomError", 179);
            
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
        
        if(empty($transaction) || empty($transaction->id)) {
            $transaction = [];
        }

        return $transaction;
    }

    public function deleteTransaction($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            $transaction = $this->getTransaction($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 5;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        if($forceFlag) {
            //wrappin db transactions
            DB::beginTransaction();

            try {
                //remove related models permanently
                $transaction->purchase()->forceDelete();
                $transaction->sale()->forceDelete();
                $transaction->employeeWage()->forceDelete();
                $transaction->expense()->forceDelete();
                $transaction->voucher()->forceDelete();

                $transaction->forceDelete();
                DB::commit();

                $deleteFlag = true;
            } catch (Exception $e) {
                DB::rollback();

                if($e->getMessage() == "CustomError") {
                    throw new AppCustomException($e->getCode(), $e->getCode());
                } else {
                    throw new AppCustomException("CustomError", ($repositoryCode."/D/02"));
                }
            }
        } else {
            //wrappin db transactions
            DB::beginTransaction();

            try {
                //related models will be deleted by deleting event handlers
                $transaction->delete();
                
                DB::commit();

                $deleteFlag = true;
            } catch (Exception $e) {
                DB::rollback();

                if($e->getMessage() == "CustomError") {
                    throw new AppCustomException($e->getCode(), $e->getCode());
                } else {
                    throw new AppCustomException("CustomError", ($repositoryCode."/D/03"));
                }
            }
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
