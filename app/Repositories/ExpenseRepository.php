<?php

namespace App\Repositories;

use App\Models\Expense;
use Exception;
use App\Exceptions\AppCustomException;

class ExpenseRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.ExpenseRepository');
    }

    /**
     * Return expenses.
     */
    public function getExpenses($params=[], $relationalParams=[], $noOfRecords=null)
    {
        $expenses = [];

        try {
            $expenses = Expense::with(['branch', 'transaction.debitAccount'])->active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $expenses = $expenses->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            foreach ($relationalParams as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $expenses = $expenses->whereHas($param['relation'], function($qry) use($param) {
                        $qry->where($param['paramName'], $param['paramValue']);
                    });
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $expenses = $expenses->paginate($noOfRecords);
            } else {
                $expenses= $expenses->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $expenses;
    }

    /**
     * Action for expense save.
     */
    public function saveExpense($inputArray=[], $expense=null)
    {
        $saveFlag   = false;

        try {
            //expense saving
            if(empty($expense)) {
                $expense = new Expense;
            }
            $expense->transaction_id = $inputArray['transaction_id'];
            $expense->date           = $inputArray['date'];
            $expense->service_id     = $inputArray['service_id'];
            $expense->bill_amount    = $inputArray['bill_amount'];
            $expense->branch_id      = $inputArray['branch_id'];
            $expense->status         = 1;
            //expense save
            $expense->save();

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
                'id'    => $expense->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * return expense.
     */
    public function getExpense($id)
    {
        $expense = [];

        try {
            $expense = Expense::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $expense;
    }

    public function deleteExpense($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get expense
            $expense = $this->getExpense($id);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            if($forceFlag) {
                $expense->forceDelete();
            } else {
                $expense->delete();
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
            'errorCode'    => $this->repositoryCode + 6,
        ];
    }
}
