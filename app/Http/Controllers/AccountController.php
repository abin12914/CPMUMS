<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Http\Requests\AccountRegistrationRequest;
use App\Http\Requests\AccountFilterRequest;
use \Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountController extends Controller
{
    protected $accountRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(AccountRepository $accountRepo)
    {
        $this->accountRepo          = $accountRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.AccountController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AccountFilterRequest $request)
    {
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;
        $activeFlag     = !empty($request->get('status_flag')) && $request->get('status_flag') ? true : false;

        $params = [
                'relation'      => $request->get('relation_type'),
                'id'            => $request->get('account_id'),
            ];
        //when request has status_flag = true then code will not check (status = 1 condition) else will check for (status = 1)
        //Note the !activeFlag for excluding status check
        return view('accounts.list', [
            'accounts'      => $this->accountRepo->getAccounts($params, $noOfRecords, true, !$activeFlag),
            'relationTypes' => config('constants.accountRelationTypes'),
            'params'        => $params,
            'noOfRecords'   => $noOfRecords,
            'activeFlag'    => $activeFlag,
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
        //excluding the relationtype 'employee'[index = 1] for new account registration
        unset($relationTypes[1]);

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
    public function store(
        AccountRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        $id=null
    ) {
        $saveFlag           = false;
        $errorCode          = 0;
        $account            = null;
        $accountTransaction = null;

        $openingBalanceAccountId = config('constants.accountConstants.AccountOpeningBalance.id');

        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

        $destination    = '/images/accounts/'; // image file upload path
        $fileName       = "";

        //upload image
        if ($request->hasFile('image_file')) {
            $file       = $request->file('image_file');
            $extension  = $file->getClientOriginalExtension(); // getting image extension
            $fileName   = $name.'_'.time().'.'.$extension; // renaming image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
            $fileName   = $destination.$fileName;//file name for saving to db
        }

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming opening balance existency.
            $openingBalanceAccount = $this->accountRepo->getAccount($openingBalanceAccountId);

            if(!empty($id)) {
                $account = $this->accountRepo->getAccount($id);

                if($account->financial_status == 2){
                    $searchTransaction = [
                        ['paramName' => 'debit_account_id', 'paramOperator' => '=', 'paramValue' => $account->id],
                        ['paramName' => 'credit_account_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceAccountId],
                    ];
                } else {
                    $searchTransaction = [
                        ['paramName' => 'debit_account_id', 'paramOperator' => '=', 'paramValue' => $openingBalanceAccountId],
                        ['paramName' => 'credit_account_id', 'paramOperator' => '=', 'paramValue' => $account->id],
                    ];
                }

                $accountTransaction = $transactionRepo->getTransactions($searchTransaction)->first();
            }

            //save to account table
            $accountResponse   = $this->accountRepo->saveAccount([
                'account_name'      => $request->get('account_name'),
                'description'       => $request->get('description'),
                'relation'          => $request->get('relation_type'),
                'financial_status'  => $financialStatus,
                'opening_balance'   => $openingBalance,
                'name'              => $name,
                'phone'             => $request->get('phone'),
                'address'           => $request->get('address'),
                'image'             => $fileName,
                'gstin'             => strtoupper($request->get('gstin')),
                'status'            => 1,
            ], $account);

            if($accountResponse['flag']) {
                //opening balance transaction details
                if($financialStatus == 1) { //incoming [account holder gives cash to company] [Creditor]
                    $debitAccountId     = $openingBalanceAccountId; //cash flow into the opening balance account
                    $creditAccountId    = $accountResponse['id']; //newly created account id [flow out from new account]
                    $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
                } else if($financialStatus == 2){ //outgoing [company gives cash to account holder] [Debitor]
                    $debitAccountId     = $accountResponse['id']; //newly created account id [flow into new account]
                    $creditAccountId    = $openingBalanceAccountId; //flow out from the opening balance account
                    $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
                } else {
                    $debitAccountId     = $openingBalanceAccountId;
                    $creditAccountId    = $accountResponse['id']; //newly created account id
                    $particulars        = "Opening balance of ". $name . " - None";
                    $openingBalance     = 0;
                }
            } else {
                throw new AppCustomException("CustomError", $accountResponse['errorCode']);
            }

            //save to transaction table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_account_id'  => $debitAccountId,
                'credit_account_id' => $creditAccountId,
                'amount'            => $openingBalance,
                'transaction_date'  => Carbon::now()->format('Y-m-d'),
                'particulars'       => $particulars,
                'branch_id'         => 0,
            ], $accountTransaction);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
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
            if(!empty($id)) {
                return [
                    'flag'  => true,
                    'id'    => $accountResponse['id']
                ];
            }
            return redirect(route('account.show', $accountResponse['id']))->with("message","Account details saved successfully. Reference Number : ". $accountResponse['id'])->with("alert-class", "success");
        }

        if(!empty($id)) {
            return [
                'flag'          => false,
                'errorCode'    => $errorCode
            ];
        }
        
        return redirect()->back()->with("message","Failed to save the account details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $errorCode  = 0;
        $account    = [];

        try {
            $account = $this->accountRepo->getAccount($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            //throwing model not found exception when no model is fetched
            throw new ModelNotFoundException("Account", $errorCode);
        }

        return view('accounts.details', [
            'account'       => $account,
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
        $errorCode  = 0;
        $account    = [];

        $relationTypes = config('constants.accountRelationTypes');
        //excluding the relationtype 'employee'[index = 1] for account update
        unset($relationTypes[1]);

        try {
            $account = $this->accountRepo->getAccount($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Account", $errorCode);
        }

        return view('accounts.edit', [
            'account'       => $account,
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
    public function update(
        AccountRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        $id)
    {
        $updateResponse = $this->store($request, $transactionRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('account.show', $updateResponse['id']))->with("message","Account details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the account details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect()->back()->with("message", "Deletion restricted.")->with("alert-class", "error");
    }

    /**
     * return the specified resource.
     *
     * @param  int  $id
     * @return json
     */
    public function getDetails($id=null)
    {
        if(empty($id)) {
            return [
                'flag'      => false,
                'message'   => "Invalid param",
            ];
        }
        $errorCode  = 0;
        $account    = [];

        try {
            $account = $this->accountRepo->getAccount($id,false);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            return [
                'flag'      => false,
                'message'   => "Record not found".$errorCode,
            ];
        }

        return [
            'flag'      => true,
            'account'   => [
                'name'      => $account->name,
                'phone'     => $account->phone,
                'address'   => $account->address,
                'gstin'     => $account->gstin,
                'type'      => $account->type,
            ],
        ];
    }
}
