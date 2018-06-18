<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\BranchRepository;
use App\Http\Requests\BranchRegistrationRequest;
use App\Http\Requests\BranchFilterRequest;
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
        $this->errorHead            = config('settings.controller_code.Branch');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BranchFilterRequest $request)
    {
        $noOfRecords = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [];

        return view('branches.list', [
                'branchesCombo' => $this->branchRepo->getBranches(),
                'branches'      => $this->branchRepo->getBranches($params, $noOfRecords),
                'params'        => $params,
                'noOfRecords'   => $noOfRecords,
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
                'name'      => $request->get('name'),
                'place'     => $request->get('place'),
                'address'   => $request->get('address'),
            ]);

            if(!$response['flag']) {
                throw new AppCustomException("CustomError", $accountResponse['errorCode']);
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
            return redirect()->back()->with("message","Branch details saved successfully. Reference Number : ". $response['id'])->with("alert-class", "alert-success");
        }
        
        return redirect()->back()->with("message","Failed to save the branch details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "alert-danger");
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteFlag = $this->branchRepo->deleteBranch($id);
        if($deleteFlag['flag']) {
            return redirect(route('branch.index'))->with("message", "Branch details deleted successfully.")->with("alert-class", "alert-success");
        }

        return redirect(route('branch.index'))->with("message", "Deletion failed. Error Code : ". $this->errorHead. " / ". $deleteFlag['errorCode'])->with("alert-class", "alert-danger");
    }
}
