<?php

namespace App\Repositories;

use App\Models\Branch;
use App\Models\Transaction;
use \Carbon\Carbon;
use Auth;
use Exception;

class BranchRepository
{
    /**
     * Return branches.
     */
    public function getBranches($params=[], $noOfRecords=null)
    {
        $branches = Branch::active();
        
        foreach ($params as $key => $value) {
            if(!empty($value)) {
                $branches = $branches->where($key, $value);
            }
        }
        if(!empty($noOfRecords)) {
            if($noOfRecords == 1) {
                $branches = $branches->first();
            } else {
                $branches = $branches->paginate($noOfRecords);
            }
        } else {
            $branches= $branches->get();
        }
        if(empty($branches) || $branches->count() < 1) {
            $branches = [];
        }

        return $branches;
    }

    /**
     * Action for saving branch.
     */
    public function saveBranch($request)
    {
        $branchName = $request->get('branch_name');
        $place      = $request->get('place');
        $address    = $request->get('address');

        try {
            $branch = new Branch;
            $branch->name    = $branchName;
            $branch->place          = $place;
            $branch->address        = $address;
            $branch->status         = 1;
            
            if($branch->save()) {
                return [
                        'flag'  => true,
                        'id'    => $branch->id,
                    ];
            }
        } catch (Exception $e) {
            return [
                'flag'      => false,
                'errorCode' => config('settings.error_method_code.Save')."/01",
            ];
        }

        return [
                'flag'      => false,
                'errorCode' => config('settings.error_method_code.Save'). "/02",
            ];
    }

    /**
     * return branch.
     */
    public function getBranch($id)
    {
        $branch = Branch::active()->where('id', $id)->first();

        if(empty($branch) || empty($branch->id)) {
            $branch = [];
        }

        return $branch;
    }

    public function deleteBranch($id, $forceFlag=false)
    {
        $errorCode = 0;
        $branch = $this->getBranch($id);

        if(!empty($branch) && !empty($branch->id)) {
            if($forceFlag) {
                if($branch->forceDelete()) {
                    return [
                        'flag'  => true,
                        'force' => true,
                    ];
                } else {
                    $errorCode = '01';
                }
            } else {
                if($branch->delete()) {
                    return [
                        'flag'  => true,
                        'force' => false,
                    ];
                } else {
                    $errorCode = '02';
                }
            }
        } else {
            $errorCode = '03';
        }
        return [
            'flag'          => false,
            'error_code'    => config('settings.error_method_code.Delete')."/". $errorCode,
        ];
    }
}
