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
    public function getProducts($params=[], $noOfRecords=null, $inParams=[])
    {
        $products = [];

        try {
            $products = Product::active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $products = $products->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            if(!empty($inParams)) {
                $products = $products->whereIn($inParams['paramName'], $inParams['paramValue']);
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
    public function saveProduct($inputArray, $product=null)
    {
        $saveFlag   = false;

        try {
            //product saving
            if(empty($product)) {
                $product = new Product;
            }
            $product->name                      = $inputArray['name'];
            $product->hsn_code                  = $inputArray['hsn_code'];
            $product->uom_code                  = $inputArray['uom_code'];
            $product->description               = $inputArray['description'];
            $product->rate                      = $inputArray['rate'];
            $product->loading_charge_per_piece  = $inputArray['loading_charge_per_piece'];
            $product->status        = 1;
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
}
