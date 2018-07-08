<?php

namespace App\Repositories;

use App\Models\EmployeeWage;
use Exception;
use App\Exceptions\AppCustomException;

class EmployeeWageRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.EmployeeWageRepository');
    }

    /**
     * Return employeeWages.
     */
    public function getEmployeeWages($params=[], $noOfRecords=null)
    {
        $employeeWages = [];

        try {
            $employeeWages = EmployeeWage::active();
            
            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $employeeWages = $employeeWages->where($key, $value);
                }
            }
            if(!empty($noOfRecords) && $noOfRecords > 0) {
                $employeeWages = $employeeWages->paginate($noOfRecords);
            } else {
                $employeeWages= $employeeWages->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $employeeWages;
    }

    /**
     * Action for saving employeeWages.
     */
    public function saveEmployeeWage($inputArray, $employeeWage=null)
    {
        $saveFlag   = false;

        try {
            //employeeWage saving
            if(empty($employeeWage)) {
                $employeeWage = new EmployeeWage;
            }
            $employeeWage->production_id    = $inputArray['production_id'];
            $employeeWage->transaction_id   = $inputArray['transaction_id'];
            $employeeWage->from_date        = $inputArray['from_date'];
            $employeeWage->to_date          = $inputArray['to_date'];
            $employeeWage->wage_type        = $inputArray['wage_type'];
            $employeeWage->wage_amount      = $inputArray['wage_amount'];
            $employeeWage->status           = 1;
            //employeeWage save
            $employeeWage->save();

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
                'id'    => $employeeWage->id,
            ];
        }
        return [
            'flag'      => false,
            'errorCode' => $this->repositoryCode + 3,
        ];
    }

    /**
     * return employeeWage.
     */
    public function getEmployeeWage($id)
    {
        $employeeWage = [];

        try {
            $employeeWage = EmployeeWage::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $employeeWage;
    }

    public function deleteEmployeeWage($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get employeeWage
            $employeeWage = $this->getEmployeeWage($id);

            //force delete or soft delete
            //related models will be deleted by deleting event handlers
            if($forceFlag) {
                $employeeWage->forceDelete();
            } else {
                $employeeWage->delete();
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
