<?php

namespace App\Repositories;

use App\Models\Transportation;
use Exception;
use App\Exceptions\AppCustomException;

class TransportationRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.TransportationRepository');
    }

    /**
     * Return transportations.
     */
    public function getTransportations($params=[], $relationalParams=[], $noOfRecords=null)
    {
        $transportations = [];

        try {
            $transportations = Transportation::active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $transportations = $transportations->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            foreach ($relationalParams as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $transportations = $transportations->whereHas($param['relation'], function($qry) use($param) {
                        $qry->where($param['paramName'], $param['paramValue']);
                    });
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $transportations = $transportations->paginate($noOfRecords);
            } else {
                $transportations= $transportations->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $transportations;
    }

    /**
     * Action for saving transportations.
     */
    public function saveTransportation($inputArray, $transportation=null)
    {
        $saveFlag   = false;

        try {
            //transportation saving
            if(empty($transportation)) {
                $transportation = new Transportation;
            }
            $transportation->transaction_id                 = $inputArray['transaction_id'];
            $transportation->sale_id                        = $inputArray['sale_id'];
            $transportation->consignee_name                 = $inputArray['consignee_name'];
            $transportation->consignee_gstin                = $inputArray['consignee_gstin'];
            $transportation->consignee_address              = $inputArray['consignee_address'];
            $transportation->consignment_vehicle_number     = $inputArray['consignment_vehicle_number'];
            $transportation->consignment_charge             = $inputArray['consignment_charge'];
            $transportation->loading_charge_transaction_id  = $inputArray['loading_charge_transaction_id'];
            $transportation->status                     = 1;
            //transportation save
            $transportation->save();

            $saveFlag = true;
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 2;
            }dd($e);
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        if($saveFlag) {
            return [
                'flag'  => true,
                'id'    => $transportation->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * return transportation.
     */
    public function getTransportation($id)
    {
        $transportation = [];

        try {
            $transportation = Transportation::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $transportation;
    }

    public function deleteTransportation($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get transportation
            $transportation = $this->getTransportation($id);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            if($forceFlag) {
                $transportation->forceDelete();
            } else {
                $transportation->delete();
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
