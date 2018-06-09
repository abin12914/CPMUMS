<?php

namespace App\Repositories;

use App\Models\Voucher;
use App\Models\Account;
use App\Models\Transaction;
use \Carbon\Carbon;
use Auth;

class VoucherRepository
{
    /**
     * Return trucks.
     */
    public function getVouchers($params=[], $relationalOrParams=[], $noOfRecords=null)
    {
        $vouchers = Voucher::/*with('transaction')->*/where('status', 1);

        foreach ($params as $param) {
            if(!empty($param) && !empty($param['paramValue'])) {
                $vouchers = $vouchers->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                if(!empty($param['paramValue1'])) {
                    $vouchers = $vouchers->orWhere($param['paramName'], $param['paramOperator'], $param['paramValue1']);
                }
            } else {
                if(!empty($param['paramValue1'])) {
                    $vouchers = $vouchers->Where($param['paramName'], $param['paramOperator'], $param['paramValue1']);
                }
            }
        }

        foreach ($relationalOrParams as $param) {
            if(!empty($param) && !empty($param['paramValue'])) {
                $vouchers = $vouchers->whereHas($param['relation'], function($qry) use($param) {
                    $qry->where($param['paramName1'], $param['paramValue'])->orWhere($param['paramName2'], $param['paramValue']);
                });
            }
        }
        
        if(!empty($noOfRecords)) {
            if($noOfRecords == 1) {
                $vouchers = $vouchers->first();
            } else {
                $vouchers = $vouchers->paginate($noOfRecords);
            }
        } else {
            $vouchers= $vouchers->get();
        }

        if(empty($vouchers) || $vouchers->count() < 1) {
            $vouchers = [];
        }

        return $vouchers;
    }

    /**
     * Save voucher.
     */
    public function saveVoucher($request)
    {
        $transactionType    = $request->get('transaction_type');
        $accountId          = $request->get('voucher_reciept_account_id');
        $date               = Carbon::createFromFormat('d-m-Y', $request->get('date'))->format('Y-m-d');
        $description        = $request->get('description');
        $amount             = $request->get('amount');

        //getting cash account id
        $cashAccount = Account::where('account_name','Cash')->first();
        if(empty($cashAccount) || empty($cashAccount->id)) {
            return [
                    'flag'      => false,
                    'errorCode' => "01",
                ];
        }
        $cashAccountId = $cashAccount->id;

        $account = Account::find($accountId);

        //check transaction type
        if($transactionType == 1) {
            //transaction from giver to cash
            $details = "Cash recieved from : ". $account->account_name;
            $debitAccountId     = $cashAccountId; //cash account
            $creditAccountId    = $accountId; // giver account
        } else {
            //transaction from cash to reciever
            $details = "Cash paid to : ". $account->account_name;
            $debitAccountId     = $accountId;
            $creditAccountId    = $cashAccountId;
        }

        $transaction    = new Transaction;
        $transaction->debit_account_id  = $debitAccountId;
        $transaction->credit_account_id = $creditAccountId;
        $transaction->amount            = $amount;
        $transaction->transaction_date  = $date;
        $transaction->particulars       = $details. " -[". $description. "]";
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {

            $voucher = new Voucher;
            $voucher->transaction_id    = $transaction->id;
            $voucher->date              = $date;
            $voucher->transaction_type  = $transactionType;
            $voucher->amount            = $amount;
            $voucher->status            = 1;
            if($voucher->save()) {
                return [
                        'flag'  => true,
                        'id'    => $voucher->id,
                    ];
            } else {
                //delete the transaction if voucher saving failed
                $transaction->forceDelete();

                $saveFlag = '02';
            }
        } else {
            $saveFlag = '03';
        }
        return [
            'flag'  => false,
            'id'    => $saveFlag,
        ];
    }

    /**
     * Return trucks.
     */
    public function getVoucher($id)
    {   
        $voucher = Voucher::with('transaction')->where('status', 1)->where('id', $id)->first();

        if(empty($voucher) || empty($voucher->id)) {
            $voucher = [];
        }

        return $voucher;
    }

    /**
     * delete voucher.
     */
    public function deleteVoucher($id, $forceFlag=false)
    {   
        $voucher = Voucher::with('transaction')->where('status', 1)->where('id', $id)->first();

        if(!empty($voucher) && !empty($voucher->id)) {
            if($forceFlag) {
                if($voucher->transaction->forceDelete() && $voucher->forceDelete()) {
                    return [
                        'flag'  => true,
                        'force' => true,
                    ];
                } else {
                    $errorCode = "04";
                }
            } else {
                if($voucher->transaction->delete()) {
                    if($voucher->delete()) {
                        return [
                            'flag'  => true,
                            'force' => false,
                        ];
                    }
                } else {
                    $errorCode = "05";
                }
            }
        } else {
            $errorCode = "06";
        }
        return [
            'flag'      => false,
            'errorCode' => $errorCode,
        ];
    }
}
