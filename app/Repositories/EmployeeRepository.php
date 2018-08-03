<?php

namespace App\Repositories;

use App\Models\Employee;
use Exception;
use App\Exceptions\AppCustomException;

class EmployeeRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.EmployeeRepository');
    }

    /**
     * Return accounts.
     */
    public function getEmployees($params=[], $noOfRecords=null)
    {
        $employees = [];

        try {
            $employees = Employee::with('account')->active();

            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $employees = $employees->where($key, $value);
                }
            }
            if(!empty($noOfRecords)) {
                $employees = $employees->paginate($noOfRecords);
            } else {
                $employees= $employees->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $employees;
    }

    /**
     * Action for saving accounts.
     */
    public function saveEmployee($inputArray, $employee=null)
    {
        $saveFlag = false;

        try {
            if(empty($employee)) {
                $employee = new Employee;
            }

            //employee saving
            $employee->account_id   = $inputArray['account_id'];
            $employee->wage_type    = $inputArray['wage_type'];
            $employee->wage_rate    = $inputArray['wage_rate'];
            $employee->status       = 1;
            //employee save
            $employee->save();

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
                'id'    => $employee->id,
            ];
        }

        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * return employee.
     */
    public function getEmployee($id)
    {
        $employee = [];

        try {
            $employee = Employee::with('account')->active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $employee;
    }

    public function deleteEmployee($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get employee record
            $employee   = $this->getEmployee($id);

            if($forceFlag) {
                //removing employee permanently
                $employee->forceDelete();
            } else {
                $employee->delete();
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
            'error_code'    => $this->repositoryCode + 6,
        ];
    }
}
