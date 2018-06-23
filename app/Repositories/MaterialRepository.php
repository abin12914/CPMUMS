<?php

namespace App\Repositories;

use App\Models\Material;
use Exception;
use App\Exceptions\AppCustomException;

class MaterialRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.MaterialRepository');
    }

    /**
     * Return materials.
     */
    public function getMaterials($params=[], $noOfRecords=null)
    {
        $materials = [];

        try {
            $materials = Material::active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $materials = $materials->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $materials = $materials->paginate($noOfRecords);
            } else {
                $materials= $materials->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $materials;
    }

    /**
     * Action for saving materials.
     */
    public function saveMaterial($inputArray)
    {
        /*$saveFlag   = false;

        try {
            //product saving
            $material = new Product;
            $material->name              = $inputArray['name'];
            $product->alternate_name    = $inputArray['alternate_name'];
            $product->description       = $inputArray['description'];
            $product->rate              = $inputArray['rate'];
            $product->status            = 1;
            //product save
            $product->save();

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
                'id'    => $product->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];*/
    }

    /**
     * return material.
     */
    public function getMaterial($id)
    {
        $material = [];

        try {
            $material = Material::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $material;
    }

    public function deleteProduct($id, $forceFlag=false)
    {
        /*$deleteFlag = false;

        try {
            //get product
            $product = $this->getProduct($id);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            if($forceFlag) {
                $product->forceDelete();
            } else {
                $product->delete();
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
        ];*/
    }
}
