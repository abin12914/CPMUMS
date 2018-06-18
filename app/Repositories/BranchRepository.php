<?php

namespace App\Repositories;

use App\Models\Branch;
use Exception;
use App\Exceptions\AppCustomException;

class BranchRepository
{
    public $repositoryCode, $errorCode = 0;

    public function __construct()
    {
        $this->repositoryCode = config('settings.repository_code.BranchRepository');
    }

    /**
     * Return branches.
     */
    public function getBranches($params=[], $noOfRecords=null)
    {
        $branches = [];

        try {
            $branches = Branch::active();
            
            foreach ($params as $key => $value) {
                if(!empty($value)) {
                    $branches = $branches->where($key, $value);
                }
            }
            if(!empty($noOfRecords)) {
                $branches = $branches->paginate($noOfRecords);
            } else {
                $branches= $branches->get();
            }
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 1;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $branches;
    }

    /**
     * Action for saving branch.
     */
    public function saveBranch($request)
    {
        $saveFlag = false;

        try {
            //employee saving
            $branch = new Employee;
            $branch->branch_name    = $inputArray['branch_name'];
            $branch->place          = $inputArray['place'];
            $branch->address        = $inputArray['address'];
            $branch->status         = 1;
            //branch save
            $branch->save();

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
                'id'    => $branch->id,
            ];
        }

        return [
            'flag'      => false,
            'errorCode' => $repositoryCode + 3,
        ];
    }

    /**
     * return branch.
     */
    public function getBranch($id)
    {
        $branch = [];

        try {
            $branch = Branch::active()->findOrFail($id);
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $this->errorCode = $e->getCode();
            } else {
                $this->errorCode = $this->repositoryCode + 4;
            }
            
            throw new AppCustomException("CustomError", $this->errorCode);
        }

        return $branch;
    }

    public function deleteBranch($id, $forceFlag=false)
    {
        $deleteFlag = false;

        try {
            //get branch record
            $branch   = $this->getBranch($id);

            if($forceFlag) {
                //removing branch permanently
                $branch->forceDelete();
            } else {
                $branch->delete();
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
