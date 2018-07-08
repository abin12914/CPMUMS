<?php

namespace App\Repositories;

use App\Models\Purchase;
use Exception;
use App\Exceptions\AppCustomException;

class PurchaseRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.PurchaseRepository');
    }

    /**
     * Return purchases.
     */
    public function getPurchases($params=[], $relationalParams=[], $noOfRecords=null)
    {
        $purchases = [];

        try {
            $purchases = Purchase::active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $purchases = $purchases->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            foreach ($relationalParams as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $purchases = $purchases->whereHas($param['relation'], function($qry) use($param) {
                        $qry->where($param['paramName'], $param['paramValue']);
                    });
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $purchases = $purchases->paginate($noOfRecords);
            } else {
                $purchases= $purchases->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $purchases;
    }

    /**
     * Action for saving purchases.
     */
    public function savePurchase($inputArray, $purchase=null)
    {
        $saveFlag   = false;

        try {
            if(empty($purchase)) {
                $purchase = new Purchase;
            }
            //purchase saving
            $purchase->transaction_id   = $inputArray['transaction_id'];
            $purchase->date             = $inputArray['date'];
            $purchase->material_id      = $inputArray['material_id'];
            $purchase->quantity         = $inputArray['quantity'];
            $purchase->rate             = $inputArray['rate'];
            $purchase->discount         = $inputArray['discount'];
            $purchase->total_amount     = $inputArray['total_amount'];
            $purchase->branch_id        = $inputArray['branch_id'];
            $purchase->status           = 1;
            //purchase save
            $purchase->save();

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
                'id'    => $purchase->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * return purchase.
     */
    public function getPurchase($id)
    {
        $purchase = [];

        try {
            $purchase = Purchase::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $purchase;
    }

    public function deletePurchase($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get purchase
            $purchase = $this->getPurchase($id);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            if($forceFlag) {
                $purchase->forceDelete();
            } else {
                $purchase->delete();
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
