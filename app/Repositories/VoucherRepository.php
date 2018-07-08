<?php

namespace App\Repositories;

use App\Models\Voucher;
use Exception;
use App\Exceptions\AppCustomException;

class VoucherRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.VoucherRepository');
    }

    /**
     * Return trucks.
     */
    public function getVouchers($params=[], $relationalOrParams=[], $whereInParams=[], $noOfRecords=null)
    {
        $vouchers = [];

        try {
            $vouchers = Voucher::with(['transaction'])->active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $vouchers = $vouchers->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            foreach ($whereInParams as $param) {
                if(!empty($param) && !empty($param['paramValue']) && count($param['paramValue']) > 0) {
                    $vouchers = $vouchers->whereIn($param['paramName'], $param['paramValue']);
                }
            }
            foreach ($relationalOrParams as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $vouchers = $vouchers->whereHas($param['relation'], function($qry) use($param) {
                        $qry->where($param['paramName1'], $param['paramValue'])->orWhere($param['paramName2'], $param['paramValue']);
                    });
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $vouchers = $vouchers->paginate($noOfRecords);
            } else {
                $vouchers= $vouchers->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }

            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $vouchers;
    }

    /**
     * Save voucher.
     */
    public function saveVoucher($inputArray=[], $voucher=null)
    {
        $saveFlag = false;

        try {
            //transaction saving
            if(empty($voucher)) {
                $voucher = new Voucher;
            }
            $voucher->transaction_id    = $inputArray['transaction_id'];
            $voucher->date              = $inputArray['date'];
            $voucher->voucher_type      = $inputArray['voucher_type'];
            $voucher->amount            = $inputArray['amount'];
            $voucher->status            = 1;
            //voucher save
            $voucher->save();
            
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
                'id'    => $voucher->id,
            ];
        }
        
        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * Return trucks.
     */
    public function getVoucher($id)
    {
        $voucher = [];

        try {
            $voucher = Voucher::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $voucher;
    }

    /**
     * delete voucher.
     */
    public function deleteVoucher($id, $forceFlag=false)
    {   
        $deleteFlag = false;

        try {
            //get voucher
            $voucher = $this->getVoucher($id);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            if($forceFlag) {
                $voucher->forceDelete();
            } else {
                $voucher->delete();
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
