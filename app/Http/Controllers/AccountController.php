<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountRepository;
use App\Http\Requests\AccountRegistrationRequest;
use App\Http\Requests\AccountFilterRequest;

class AccountController extends Controller
{
    protected $accountRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(AccountRepository $accountRepo)
    {
        $this->accountRepo          = $accountRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.error_heads.Account');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AccountFilterRequest $request)
    {
        $noOfRecords = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
                'relation'  => $request->get('relation_type'),
                'id'        => $request->get('account_id'),
            ];

        return view('accounts.list', [
                'accountsCombo' => $this->accountRepo->getAccounts(),
                'accounts'      => $this->accountRepo->getAccounts($params, $noOfRecords),
                'relationTypes' => config('constants.accountRelationTypes'),
                'accountTypes'  => config('constants.accountTypes'),
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
        $relationTypes = config('constants.accountRelationTypes');
        //excluding the relationtype 'employee'[index = 5] for new account registration
        unset($relationTypes[5]);

        return view('accounts.register', [
                'relationTypes' => $relationTypes,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRegistrationRequest $request)
    {
        $response   = $this->accountRepo->saveAccount($request);

        if($response['flag']) {
            return redirect()->back()->with("message","Account details saved successfully. Reference Number : ". $response['id'])->with("alert-class", "alert-success");
        }
        
        return redirect()->back()->with("message","Failed to save the account details. Error Code : ". $this->errorHead. "/". $response['errorCode'])->with("alert-class", "alert-danger");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('accounts.details', [
                'account'       => $this->accountRepo->getAccount($id),
                'relationTypes' => config('constants.accountRelationTypes'),
                'accountTypes'  => config('constants.$accountTypes'),
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
        $relationTypes = config('constants.accountRelationTypes');
        //excluding the relationtype 'employee'[index = 5] for account update
        unset($relationTypes[5]);

        return view('accounts.edit', [
                'account'       => $this->accountRepo->getAccount($id),
                'relationTypes' => $relationTypes,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountRegistrationRequest $request, $id)
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
        $account = $this->accountRepo->getAccount($id);

        if(!empty($account) && !empty($account->id)) {
            if($account->relation != 5) {
                $deleteFlag = $this->accountRepo->deleteAccount($id);
                if($deleteFlag['flag']) {
                    return redirect(route('account.index'))->with("message", "Account details deleted successfully.")->with("alert-class", "alert-success");
                }
            } else {
                return redirect(route('account.index'))->with("message", "Deletion failed. Employee account should be deleted from employee records.")->with("alert-class", "alert-danger");
            }
        }

        return redirect(route('account.index'))->with("message", "Deletion failed. Error Code : ". $this->errorHead. " / ". $deleteFlag['errorCode'])->with("alert-class", "alert-danger");
    }
}
