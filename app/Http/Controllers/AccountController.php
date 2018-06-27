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
        $this->errorHead            = config('settings.controller_code.Account');
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
                'accounts'      => $this->accountRepo->getAccounts($params, $noOfRecords),
                'relationTypes' => config('constants.accountRelationTypes'),
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
    public function store(AccountRegistrationRequest $request, TransactionRepository $transactionRepo)
    {
        $saveFlag       = false;
        $errorCode      = 0;

        $openingBalanceAccountId = config('constants.accountConstants.AccountOpeningBalance.id');

        $destination    = '/images/accounts/'; // image file upload path
        $fileName       = "";

        //upload image
        if ($request->hasFile('image_file')) {
            $file       = $request->file('image_file');
            $extension  = $file->getClientOriginalExtension(); // getting image extension
            $fileName   = $name.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
            $fileName   = $destination.$fileName;//file name for saving to db
        }

        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $name               = $request->get('name');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming opening balance existency.
            $openingBalanceAccount = $this->accountRepo->getAccount($openingBalanceAccountId);

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
                'status'            => 1,
            ]);

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
            ]);

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
            return redirect()->back()->with("message","Account details saved successfully. Reference Number : ". $accountResponse['id'])->with("alert-class", "alert-success");
        }
        
        return redirect()->back()->with("message","Failed to save the account details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "alert-danger");
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
            //throwing methodnotfound exception when no model is fetched
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
        //excluding the relationtype 'employee'[index = 5] for account update
        unset($relationTypes[5]);

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
        return redirect()->back()->with("message", "Deletion restricted.")->with("alert-class", "alert-danger");
        
        $deleteFlag['flag'] = false;
        try {
            $account = $this->accountRepo->getAccount($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 4;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Account", $errorCode);
        }

        if(!empty($account) && !empty($account->id)) {
            if($account->relation != 5) {
                
                //wrappin db transactions
                DB::beginTransaction();

                try {
                    $deleteFlag = $this->accountRepo->deleteAccount($id);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();

                    if($e->getMessage() == "CustomError") {
                        $errorCode = $e->getCode();
                    } else {
                        $errorCode = 5;
                    }
                }
                if($deleteFlag['flag']) {
                    return redirect(route('account.index'))->with("message", "Account details deleted successfully.")->with("alert-class", "alert-success");
                }
            } else {
                return redirect(route('account.index'))->with("message", "Deletion failed. Employee account should be deleted from employee records.")->with("alert-class", "alert-danger");
            }
        }

        return redirect(route('account.index'))->with("message", "Deletion failed. Error Code : ". $this->errorHead. " / ". $errorCode)->with("alert-class", "alert-danger");
    }
}
