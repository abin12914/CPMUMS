<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Models\Service;
use App\Models\Account;
use App\Models\Transaction;
use \Carbon\Carbon;
use Auth;

class ExpenseRepository
{
    /**
     * Return services.
     */
    public function getServices()
    {
        $services = [];

        $services = Service::orderBy('name')->get();

        return $services;
    }

    /**
     * Return expenses.
     */
    public function getExpenses($params=[], $relationalParams=[], $noOfRecords=null)
    {
        $expenses = Expense::with(['truck','transaction.creditAccount', 'service'])->where('status', 1);

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
        
        if(!empty($noOfRecords)) {
            if($noOfRecords == 1) {
                $expenses = $expenses->first();
            } else {
                $expenses = $expenses->paginate($noOfRecords);
            }
        } else {
            $expenses= $expenses->get();
        }
        if(empty($expenses) || $expenses->count() < 1) {
            $expenses = [];
        }

        return $expenses;
    }

    /**
     * Action for expense save.
     */
    public function saveExpense($request)
    {
        $transactionType    = $request->get('transaction_type');
        $supplierAccountId  = $request->get('supplier_account_id');
        $truckId            = $request->get('truck_id');
        $date               = Carbon::createFromFormat('d-m-Y', $request->get('date'))->format('Y-m-d');
        $serviceId          = $request->get('service_id');
        $description        = $request->get('description');
        $billAmount         = $request->get('bill_amount');

        //getting service and expense account id
        $expenseAccount = Account::where('account_name','Service And Expenses')->first();
        if(empty($expenseAccount) || empty($expenseAccount->id)) {
            return [
                'flag'      => false,
                'errorCode' => "01"
            ];
        }
        $expenseAccountId = $expenseAccount->id;

        if($transactionType == 1) {
            $supplierAccount = Account::find($supplierAccountId);
        } else {
            $supplierAccount = Account::where('account_name', 'Cash')->first();

            if(empty($supplierAccount) || empty($supplierAccount->id)) {
                return [
                    'flag'      => false,
                    'errorCode' => "02"
                ];
            }
        }
        $truck = Truck::find($truckId);
        $service = Service::find($serviceId);

        $transaction    = new Transaction;
        $transaction->debit_account_id  = $expenseAccountId; //service and expense account
        $transaction->credit_account_id = $supplierAccount->id; //supplier account id
        $transaction->amount            = $billAmount;
        $transaction->transaction_date  = $date;
        $transaction->particulars       = ("Service Expense : ". $truck->reg_number. " - ". $service->name. " -[". $description. "]");
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {

            $expense = new Expense;
            $expense->transaction_id = $transaction->id;
            $expense->date           = $date;
            $expense->truck_id       = $truckId;
            $expense->service_id     = $serviceId;
            $expense->bill_amount    = $billAmount;
            $expense->status         = 1;
            if($expense->save()) {
                return [
                        'flag'  => true,
                        'id'    => $expense->id,
                    ];
            } else {
                //delete the transaction if expense saving failed
                $transaction->forceDelete();

                $saveFlag = '03';
            }
        } else {
            $saveFlag = '04';
        }
        return [
            'flag'  => false,
            'id'    => $saveFlag,
        ];
    }

    /**
     * return expense.
     */
    public function getExpense($id)
    {
        $expense = Expense::with(['truck','transaction.creditAccount', 'service'])->where('status', 1)->where('id', $id)->first();
        if(empty($expense) || empty($expense->id)) {
            $expense = [];
        }

        return $expense;
    }

    public function deleteExpense($id, $forceFlag=false)
    {
        $errorCode = 'Unknown';
        $expense = Expense::with('transaction')->where('status', 1)->where('id', $id)->first();

        if(!empty($expense) && !empty($expense->id)) {
            if($forceFlag) {
                if($expense->transaction->forceDelete() && $expense->forceDelete()) {
                    return [
                        'flag'  => true,
                        'force' => true,
                    ];
                } else {
                    $errorCode = '05';
                }
            } else {
                if($expense->transaction->delete()) {
                    if($expense->delete()) {
                        return [
                            'flag'  => true,
                            'force' => false,
                        ];
                    } else {
                        $errorCode = '06';
                    }
                } else {
                    $errorCode = '07';
                }
            }
        }
        return [
            'flag'          => false,
            'error_code'    => $errorCode,
        ];
    }
}
