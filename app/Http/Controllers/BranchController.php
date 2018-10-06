<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BranchRepository;
use App\Http\Requests\BranchRegistrationRequest;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BranchController extends Controller
{
    protected $branchRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(BranchRepository $branchRepo)
    {
        $this->branchRepo           = $branchRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.BranchController');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('branches.list', [
                'branches'      => $this->branchRepo->getBranches([], null),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('branches.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchRegistrationRequest $request)
    {
        $saveFlag       = false;
        $errorCode      = 0;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            $response   = $this->branchRepo->saveBranch([
                'name'              => $request->get('branch_name'),
                'place'             => $request->get('place'),
                'address'           => $request->get('address'),
                'gstin'             => $request->get('gstin'),
                'primary_phone'     => $request->get('primary_phone'),
                'secondary_phone'   => $request->get('secondary_phone'),
                'level'             => $request->get('branch_level'),
            ]);

            if(!$response['flag']) {
                throw new AppCustomException("CustomError", $response['errorCode']);
            }

            DB::commit();
            $saveFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
        }

        if($saveFlag) {
            return redirect(route('branch.index'))->with("message","Branch details saved successfully. Reference Number : ". $response['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to save the branch details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('branches.details', [
                'branch'       => $this->branchRepo->getBranch($id),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('branches.edit', [
                'branch'       => $this->branchRepo->getBranch($id),
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRegistrationRequest $request, $id)
    {
        $saveFlag       = false;
        $errorCode      = 0;

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //get branch
            $branch = $this->branchRepo->getBranch($id);

            $response   = $this->branchRepo->saveBranch([
                'name'              => $request->get('branch_name'),
                'place'             => $request->get('place'),
                'address'           => $request->get('address'),
                'gstin'             => $request->get('gstin'),
                'primary_phone'     => $request->get('primary_phone'),
                'secondary_phone'   => $request->get('secondary_phone'),
                'level'             => $request->get('branch_level'),
            ], $branch);

            if(!$response['flag']) {
                throw new AppCustomException("CustomError", $response['errorCode']);
            }

            DB::commit();
            $saveFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
        }

        if($saveFlag) {
            return redirect(route('branch.index'))->with("message","Branch details updated successfully. Updated Record Number : ". $response['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the branch details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->back()->with("message", "Branch deletion restricted.")->with("alert-class", "error");
    }
}
