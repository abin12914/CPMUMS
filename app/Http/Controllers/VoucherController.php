<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VoucherRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Http\Requests\VoucherRegistrationRequest;
use App\Http\Requests\VoucherFilterRequest;
use \Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VoucherController extends Controller
{
    protected $voucherRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

     public function __construct(VoucherRepository $voucherRepo)
    {
        $this->voucherRepo  = $voucherRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.VoucherController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VoucherFilterRequest $request)
    {
        $fromDate       = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : "";
        $toDate         = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : "";
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
            'from_date' =>  [
                'paramName'     => 'date',
                'paramOperator' => '>=',
                'paramValue'    => $fromDate,
            ],
            'to_date'   =>  [
                'paramName'     => 'date',
                'paramOperator' => '<=',
                'paramValue'    => $toDate,
            ],
        ];

        $whereInParams = [
            'voucher_type'  =>  [
                'paramName'     => 'voucher_type',
                'paramValue'    => $request->get('voucher_type'),
            ],
        ];

        $relationalOrParams = [
            'voucher_account_id'    =>  [
                'relation'      => 'transaction',
                'paramName1'    => 'debit_account_id',
                'paramName2'    => 'credit_account_id',
                'paramValue'    => $request->get('voucher_account_id'),
            ],
        ];

        $vouchers       = $this->voucherRepo->getVouchers($params, $relationalOrParams, $whereInParams, $noOfRecords);
        $voucherRecords = $this->voucherRepo->getVouchers($params, $relationalOrParams, $whereInParams, null);

        //params passing for auto selection
        $params['from_date']['paramValue'] = $request->get('from_date');
        $params['to_date']['paramValue'] = $request->get('to_date');
        $params = array_merge($params, $whereInParams);
        $params = array_merge($params, $relationalOrParams);

        return view('vouchers.list', [
                'vouchers'          => $vouchers,
                'params'            => $params,
                'noOfRecords'       => $noOfRecords,
                'totalDebitAmount'  => $voucherRecords->where('voucher_type', 1)->sum('amount'),
                'totalCreditAmount' => $voucherRecords->where('voucher_type', 2)->sum('amount'),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vouchers.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        VoucherRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        $id=null
    ) {
        $saveFlag           = false;
        $errorCode          = 0;
        $voucher            = null;
        $voucherTransaction = null;

        $cashAccountId      = config('constants.accountConstants.Cash.id');
        $transactionDate    = Carbon::createFromFormat('d-m-Y', $request->get('date'))->format('Y-m-d');
        $voucherType        = $request->get('voucher_type');
        $voucherTitle       = $voucherType == 1 ? "Reciept" : "Payemnt";
        $accountId          = $request->get('voucher_account_id');
        $description        = $request->get('description');
        $amount             = $request->get('amount');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            if(!empty($id)) {
                $voucher = $this->voucherRepo->getVoucher($id);
                $voucherTransaction = $transactionRepo->getTransaction($voucher->transaction_id);
            }
            //confirming cash account existency.
            $cashAccount = $accountRepo->getAccount($cashAccountId);

            //accessing account existency.
            $account = $accountRepo->getAccount($accountId, false);

            if($voucherType == 1) {
                //Receipt : Debit cash account - Credit giver account
                $debitAccountId     = $cashAccountId;
                $creditAccountId    = $accountId;
                $particulars        = $description. "[Cash received from ". $account->account_name. "]";
            } else {
                //Payment : Debit receiver account - Credit cash account
                $debitAccountId     = $accountId;
                $creditAccountId    = $cashAccountId;
                $particulars        = $description. "[Cash paid to ". $account->account_name. "]";
            }

            //save voucher transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_account_id'  => $debitAccountId,
                'credit_account_id' => $creditAccountId,
                'amount'            => $amount ,
                'transaction_date'  => $transactionDate,
                'particulars'       => $particulars,
                'branch_id'         => null,
            ], $voucherTransaction);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            //save to voucher table
            $voucherResponse = $this->voucherRepo->saveVoucher([
                'transaction_id' => $transactionResponse['id'],
                'date'           => $transactionDate,
                'voucher_type'   => $voucherType,
                'amount'         => $amount,
            ], $voucher);

            if(!$voucherResponse['flag']) {
                throw new AppCustomException("CustomError", $voucherResponse['errorCode']);
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
                    'id'    => $voucherResponse['id']
                ];
            }
            return redirect(route('voucher.show', $voucherResponse['id']))->with("message", $voucherTitle. " details saved successfully. Reference Number : ". $transactionResponse['id'])->with("alert-class", "success");
        }
        
        if(!empty($id)) {
            return [
                'flag'      => false,
                'errorCode' => $errorCode
            ];
        }
        return redirect()->back()->with("message","Failed to save the ". $voucherTitle. " details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
        $voucher       = [];

        try {
            $voucher = $this->voucherRepo->getVoucher($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Voucher", $errorCode);
        }

        return view('vouchers.details', [
            'voucher' => $voucher,
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
        $voucher       = [];

        try {
            $voucher = $this->voucherRepo->getVoucher($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Voucher", $errorCode);
        }

        return view('vouchers.edit', [
            'voucher' => $voucher,
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
        VoucherRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        $id
    ) {
        $updateResponse = $this->store($request, $transactionRepo, $accountRepo, $id);

        if($updateResponse['flag']) {
            return redirect(route('voucher.show', $updateResponse['id']))->with("message","Voucher details updated successfully. Updated Record Number : ". $updateResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the voucher details. Error Code : ". $this->errorHead. "/". $updateResponse['errorCode'])->with("alert-class", "error");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteFlag = false;
        $errorCode  = 0;

        //wrapping db transactions
        DB::beginTransaction();
        try {
            $deleteResponse = $this->voucherRepo->deleteVoucher($id);
            
            if(!$deleteResponse['flag']) {
                throw new AppCustomException("CustomError", $deleteResponse['errorCode']);
            }
            
            DB::commit();
            $deleteFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 5;
            }
        }

        if($deleteFlag) {
            return redirect(route('voucher.index'))->with("message","Voucher details deleted successfully.")->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to delete the voucher details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
