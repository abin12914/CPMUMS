<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SaleRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Repositories\TransportationRepository;
use App\Http\Requests\SaleRegistrationRequest;
use App\Http\Requests\SaleFilterRequest;
use Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SaleController extends Controller
{
    protected $saleRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(SaleRepository $saleRepo)
    {
        $this->saleRepo             = $saleRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.SaleController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SaleFilterRequest $request)
    {
        $fromDate       = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : "";
        $toDate         = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : "";
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
            'from_date'    =>  [
                'paramName'     => 'date',
                'paramOperator' => '>=',
                'paramValue'    => $fromDate,
            ],
            'to_date'   =>  [
                'paramName'     => 'date',
                'paramOperator' => '<=',
                'paramValue'    => $toDate,
            ],
            'branch_id' =>  [
                'paramName'     => 'branch_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('branch_id'),
            ]
        ];

        $relationalParams = [
            'customer_type' =>  [
                'relation'      => 'transaction.debitAccount',
                'paramName'     => 'status',
                'paramValue'    => $request->get('customer_type'),
            ],
            'customer_account_id'   =>  [
                'relation'      => 'transaction',
                'paramName'     => 'debit_account_id',
                'paramValue'    => $request->get('customer_account_id'),
            ]
        ];

        $sales          = $this->saleRepo->getSales($params, $relationalParams, $noOfRecords);
        $totalAmount    = $this->saleRepo->getSales($params, $relationalParams, null)->sum('total_amount');

        //params passing for auto selection
        $params['from_date']['paramValue'] = $request->get('from_date');
        $params['to_date']['paramValue'] = $request->get('to_date');
        $params = array_merge($params, $relationalParams);

        return view('sales.list', [
            'saleRecords'   => $sales,
            'totalAmount'   => $totalAmount,
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
        return view('sales.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        SaleRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        TransportationRepository $transportationRepo
    ) {
        $saveFlag   = false;
        $errorCode  = 0;
        $wageAmount = 0;

        $saleAccountId                  = config('constants.accountConstants.Sale.id');
        $transportationChargeAccountId    = config('constants.accountConstants.TransportationChargeAccount.id');

        $transactionDate        = Carbon::createFromFormat('d-m-Y', $request->get('sale_date'))->format('Y-m-d');
        $branchId               = $request->get('branch_id');
        $saleType               = $request->get('sale_type');
        $products               = $request->get('product_id');
        $totalBill              = $request->get('total_bill');
        $transportationCharge   = $request->get('transportation_charge');
        $transportationLocation = $request->get('transportation_location');

        foreach ($products as $index => $productId) {
            if(!empty($request->get('sale_quantity')[$index]) && !empty($request->get('sale_rate')[$index])) {
                $productArray[$productId] = [
                    'quantity'  => $request->get('sale_quantity')[$index],
                    'rate'      => $request->get('sale_rate')[$index],
                ];
            }
        }

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming sale account existency.
            $saleAccount = $accountRepo->getAccount($saleAccountId);
            //confirming transportation charge account existency.
            $transportationChargeAccount = $accountRepo->getAccount($transportationChargeAccountId);

            if($saleType != 1) {
                $customerName       = $request->get('name');
                $customerPhone      = $request->get('phone');

                //checking for exist-ency of the account
                $accounts = $accountRepo->getAccounts(['phone' => $customerPhone],null,null,false);

                if(empty($accounts) || count($accounts) == 0)
                {
                    //save short term customer account to table
                    $accountResponse = $accountRepo->saveAccount([
                        'account_name'      => $customerName,
                        'description'       => ("Short term credit account of". $customerName),
                        'relation'          => 3, //customer
                        'financial_status'  => 0,
                        'opening_balance'   => 0,
                        'name'              => $customerName,
                        'phone'             => $customerPhone,
                        'address'           => $customerName. " - ". $customerPhone,
                        'image'             => null,
                        'status'            => 2, //short term credit account
                    ]);

                    if(!$accountResponse['flag']) {
                        throw new AppCustomException("CustomError", $accountResponse['errorCode']);
                    }
                    $customerAccountId  = $accountResponse['id'];
                    $particulars        = ("Sale invoice of Rs.". $totalBill. "/- generated for ". $customerName. "-". $customerPhone);
                } else {
                    $customerAccountId = $accounts->first()->id;
                    //accessing debit account
                    $customerAccount = $accountRepo->getAccount($customerAccountId, false);
                    $particulars = ("Sale invoice of Rs.". $totalBill. "/- generated for ". $customerAccount->account_name. "-". $customerAccount->phone);
                }
            } else {
                $customerAccountId = $request->get('customer_account_id');
                //accessing debit account
                $customerAccount = $accountRepo->getAccount($customerAccountId, false);
                $particulars = ("Sale invoice of Rs.". $totalBill. "/- generated for ". $customerAccount->account_name);
            }

            //save sale transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_account_id'  => $customerAccountId, // debit the customer
                'credit_account_id' => $saleAccountId, // credit the sale account
                'amount'            => $totalBill ,
                'transaction_date'  => $transactionDate,
                'particulars'       => $particulars,
                'branch_id'         => $branchId,
            ]);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            //save to sale table
            $saleResponse = $this->saleRepo->saveSale([
                'transaction_id' => $transactionResponse['id'],
                'date'           => $transactionDate,
                'productsArray'  => $productArray,
                'discount'       => $request->get('sale_discount'),
                'total_amount'   => $totalBill,
                'branch_id'      => $branchId,
            ]);

            if(!$saleResponse['flag']) {
                throw new AppCustomException("CustomError", $saleResponse['errorCode']);
            }

            //save transportation transaction to table
            $transportationTransactionResponse = $transactionRepo->saveTransaction([
                'debit_account_id'  => $customerAccountId, // debit the customer
                'credit_account_id' => $transportationChargeAccountId, // credit the transportation charge account
                'amount'            => $transportationCharge ,
                'transaction_date'  => $transactionDate,
                'particulars'       => ("Transportation charge to ". $transportationLocation. ". Sale Invoice No:". $saleResponse['id']),
                'branch_id'         => $branchId,
            ]);

            if(!$transportationTransactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transportationTransactionResponse['errorCode']);
            }

            //save to sale table
            $transportationResponse = $transportationRepo->saveTransportation([
                'transaction_id'            => $transportationTransactionResponse['id'],
                'sale_id'                   => $saleResponse['id'],
                'transportation_location'   => $transportationLocation,
                'transportation_charge'     => $transportationCharge,
            ]);

            if(!$transportationResponse['flag']) {
                throw new AppCustomException("CustomError", $transportationResponse['errorCode']);
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
            return redirect(route('sale.index'))->with("message","Sale details saved successfully. Reference Number : ". $transactionResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to save the sale details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
        $sale       = [];

        try {
            $sale = $this->saleRepo->getSale($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Sale", $errorCode);
        }

        return view('sales.details', [
            'sale' => $sale,
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
        $sale       = [];

        try {
            $sale = $this->saleRepo->getSale($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Sale", $errorCode);
        }

        return view('sales.edit', [
            'sale' => $sale,
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
        SaleRegistrationRequest $request,
        $id,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        TransportationRepository $transportationRepo
    ) {
        $saveFlag   = false;
        $errorCode  = 0;
        $wageAmount = 0;
        $transportationTransaction = null;

        $saleAccountId                  = config('constants.accountConstants.Sale.id');
        $transportationChargeAccountId  = config('constants.accountConstants.TransportationChargeAccount.id');

        $transactionDate        = Carbon::createFromFormat('d-m-Y', $request->get('sale_date'))->format('Y-m-d');
        $branchId               = $request->get('branch_id');
        $saleType               = $request->get('sale_type');
        $products               = $request->get('product_id');
        $totalBill              = $request->get('total_bill');
        $transportationCharge   = $request->get('transportation_charge');
        $transportationLocation = $request->get('transportation_location');

        foreach ($products as $index => $productId) {
            if(!empty($request->get('sale_quantity')[$index]) && !empty($request->get('sale_rate')[$index])) {
                $productArray[$productId] = [
                    'quantity'  => $request->get('sale_quantity')[$index],
                    'rate'      => $request->get('sale_rate')[$index],
                ];
            }
        }

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //get sale
            $sale = $this->saleRepo->getSale($id);
            //get sale transaction
            $saleTransaction = $transactionRepo->getTransaction($sale->transaction_id);
            //get sale transportation
            $transportation = $transportationRepo->getTransportations([['paramName' => 'sale_id', 'paramOperator' => '=', 'paramValue' => $sale->id]])->first();
            if(!empty($transportation)) {
                $transportationTransaction = $transactionRepo->getTransaction($transportation->transaction_id);
            }

            //confirming sale account existency.
            $saleAccount = $accountRepo->getAccount($saleAccountId);
            //confirming transportation charge account existency.
            $transportationChargeAccount = $accountRepo->getAccount($transportationChargeAccountId);

            if($saleType != 1) {
                $customerName       = $request->get('name');
                $customerPhone      = $request->get('phone');

                //checking for exist-ency of the account
                $accounts = $accountRepo->getAccounts(['phone' => $customerPhone],null,null,false);

                if(empty($accounts) || count($accounts) == 0)
                {
                    //save short term customer account to table
                    $accountResponse = $accountRepo->saveAccount([
                        'account_name'      => $customerName,
                        'description'       => ("Short term credit account of". $customerName),
                        'relation'          => 3, //customer
                        'financial_status'  => 0,
                        'opening_balance'   => 0,
                        'name'              => $customerName,
                        'phone'             => $customerPhone,
                        'address'           => $customerName. " - ". $customerPhone,
                        'image'             => null,
                        'status'            => 2, //short term credit account
                    ]);

                    if(!$accountResponse['flag']) {
                        throw new AppCustomException("CustomError", $accountResponse['errorCode']);
                    }
                    $customerAccountId  = $accountResponse['id'];
                    $particulars        = ("Sale invoice of Rs.". $totalBill. "/- generated for ". $customerName. "-". $customerPhone);
                } else {
                    $customerAccountId = $accounts->first()->id;
                    //accessing debit account
                    $customerAccount = $accountRepo->getAccount($customerAccountId, false);
                    $particulars = ("Sale invoice of Rs.". $totalBill. "/- generated for ". $customerAccount->account_name. "-". $customerAccount->phone);
                }
            } else {
                $customerAccountId = $request->get('customer_account_id');
                //accessing debit account
                $customerAccount = $accountRepo->getAccount($customerAccountId, false);
                $particulars = ("Sale invoice of Rs.". $totalBill. "/- generated for ". $customerAccount->account_name);
            }

            //save sale transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_account_id'  => $customerAccountId, // debit the customer
                'credit_account_id' => $saleAccountId, // credit the sale account
                'amount'            => $totalBill ,
                'transaction_date'  => $transactionDate,
                'particulars'       => $particulars,
                'branch_id'         => $branchId,
            ], $saleTransaction);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            //save to sale table
            $saleResponse = $this->saleRepo->saveSale([
                'transaction_id' => $transactionResponse['id'],
                'date'           => $transactionDate,
                'productsArray'  => $productArray,
                'discount'       => $request->get('sale_discount'),
                'total_amount'   => $totalBill,
                'branch_id'      => $branchId,
            ], $sale);

            if(!$saleResponse['flag']) {
                throw new AppCustomException("CustomError", $saleResponse['errorCode']);
            }

            //save transportation transaction to table
            $transportationTransactionResponse = $transactionRepo->saveTransaction([
                'debit_account_id'  => $customerAccountId, // debit the customer
                'credit_account_id' => $transportationChargeAccountId, // credit the transportation charge account
                'amount'            => $transportationCharge ,
                'transaction_date'  => $transactionDate,
                'particulars'       => ("Transportation charge to ". $transportationLocation. ". Sale Invoice No:". $saleResponse['id']),
                'branch_id'         => $branchId,
            ], $transportationTransaction);

            if(!$transportationTransactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transportationTransactionResponse['errorCode']);
            }

            //save to sale table
            $transportationResponse = $transportationRepo->saveTransportation([
                'transaction_id'            => $transportationTransactionResponse['id'],
                'sale_id'                   => $saleResponse['id'],
                'transportation_location'   => $transportationLocation,
                'transportation_charge'     => $transportationCharge,
            ], $transportation);

            if(!$transportationResponse['flag']) {
                throw new AppCustomException("CustomError", $transportationResponse['errorCode']);
            }

            DB::commit();
            $saveFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 4;
            }
        }

        if($saveFlag) {
            return redirect(route('sale.show', $sale->id))->with("message","Sale details updated successfully. Updated Record Number : ". $transactionResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the sale details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
            $deleteResponse = $this->saleRepo->deleteSale($id);
            
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
            return redirect(route('sale.index'))->with("message","Sale details deleted successfully.")->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to delete the sale details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Show the invoice for print.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice($id)
    {
        $errorCode  = 0;
        $sale       = [];

        try {
            $sale = $this->saleRepo->getSale($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 6;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Sale", $errorCode);
        }

        return view('sales.invoice', [
            'sale' => $sale,
        ]);
    }
}
