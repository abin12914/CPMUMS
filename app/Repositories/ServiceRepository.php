<?php

namespace App\Repositories;

use App\Models\Service;
use Exception;
use App\Exceptions\AppCustomException;

class ServiceRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.ServiceRepository');
    }

    /**
     * Return services.
     */
    public function getServices($params=[], $noOfRecords=null)
    {
        $services = [];

        try {
            $services = Service::active();

            foreach ($params as $param) {
                if(!empty($param) && !empty($param['paramValue'])) {
                    $services = $services->where($param['paramName'], $param['paramOperator'], $param['paramValue']);
                }
            }

            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $services = $services->paginate($noOfRecords);
            } else {
                $services= $services->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $services;
    }

    /**
     * Action for saving services.
     */
    public function saveService($inputArray)
    {
        $saveFlag   = false;

        try {
            //service saving
            $service = new Service;
            $service->name              = $inputArray['name'];
            $service->alternate_name    = $inputArray['alternate_name'];
            $service->description       = $inputArray['description'];
            $service->rate              = $inputArray['rate'];
            $service->status            = 1;
            //service save
            $service->save();

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
                'id'    => $service->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * return service.
     */
    public function getService($id)
    {
        $service = [];

        try {
            $service = Service::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $service;
    }

    public function deleteService($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get service
            $service = $this->getService($id);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            if($forceFlag) {
                $service->forceDelete();
            } else {
                $service->delete();
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
