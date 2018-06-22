<?php

namespace App\Repositories;

use App\Models\Product;
use Exception;
use App\Exceptions\AppCustomException;

class ProductRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.ProductRepository');
    }

    /**
     * Return products.
     */
    public function getProducts($params=[], $noOfRecords=null)
    {
        $products = [];

        try {
            $products = Product::active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $products = $products->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $products = $products->paginate($noOfRecords);
            } else {
                $products= $products->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $products;
    }

    /**
     * Action for saving products.
     */
    public function saveProduct($inputArray)
    {
        $saveFlag   = false;

        try {
            //product saving
            $product = new Product;
            $product->name              = $inputArray['name'];
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
        ];
    }

    /**
     * return product.
     */
    public function getProduct($id)
    {
        $product = [];

        try {
            $product = Product::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $product;
    }

    public function deleteProduct($id, $forceFlag=false)
    {
        $deleteFlag = false;

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
        ];
    }
}
