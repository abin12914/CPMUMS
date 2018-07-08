<?php

namespace App\Repositories;

use App\Models\Production;
use Exception;
use App\Exceptions\AppCustomException;

class ProductionRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.ProductionRepository');
    }

    /**
     * Return productions.
     */
    public function getProductions($params=[], $noOfRecords=null)
    {
        $productions = [];

        try {
            $productions = Production::active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $productions = $productions->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $productions = $productions->paginate($noOfRecords);
            } else {
                $productions= $productions->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $productions;
    }

    /**
     * Action for saving productions.
     */
    public function saveProduction($inputArray, $production=null)
    {
        $saveFlag   = false;

        try {
            //production saving
            if(empty($production)) {
                $production = new Production;
            }

            $production->date           = $inputArray['date'];
            $production->branch_id      = $inputArray['branch_id'];
            $production->employee_id    = $inputArray['employee_id'];
            $production->product_id     = $inputArray['product_id'];
            $production->mould_quantity = $inputArray['mould_quantity'];
            $production->piece_quantity = $inputArray['piece_quantity'];
            $production->status         = 1;
            //production save
            $production->save();

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
                'id'    => $production->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * return production.
     */
    public function getProduction($id)
    {
        $production = [];

        try {
            $production = Production::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $production;
    }

    public function deleteProduction($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get production
            $production = $this->getProduction($id);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            if($forceFlag) {
                $production->forceDelete();
            } else {
                $production->delete();
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
