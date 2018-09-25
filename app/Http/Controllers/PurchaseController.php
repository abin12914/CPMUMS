<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PurchaseRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Repositories\MaterialRepository;
use App\Http\Requests\PurchaseRegistrationRequest;
use App\Http\Requests\PurchaseFilterRequest;
use Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PurchaseController extends Controller
{
    protected $purchaseRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(PurchaseRepository $purchaseRepo)
    {
        $this->purchaseRepo         = $purchaseRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.PurchaseController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PurchaseFilterRequest $request)
    {
        $fromDate       = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : "";
        $toDate         = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : "";
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
            'from_date'     =>  [
                'paramName'     => 'date',
                'paramOperator' => '>=',
                'paramValue'    => $fromDate,
            ],
            'to_date'       =>  [
                'paramName'     => 'date',
                'paramOperator' => '<=',
                'paramValue'    => $toDate,
            ],
            'branch_id'     =>  [
                'paramName'     => 'branch_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('branch_id'),
            ],
            'material_id'   =>  [
                'paramName'     => 'material_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('material_id'),
            ],
        ];

        $relationalParams = [
            'supplier_account_id'   =>  [
                'relation'      => 'transaction',
                'paramName'     => 'credit_account_id',
                'paramValue'    => $request->get('supplier_account_id'),
            ]
        ];

        $purchases      = $this->purchaseRepo->getPurchases($params, $relationalParams, $noOfRecords);
        $totalAmount    = $this->purchaseRepo->getPurchases($params, $relationalParams, null)->sum('total_amount');

        //params passing for auto selection
        $params['from_date']['paramValue'] = $request->get('from_date');
        $params['to_date']['paramValue'] = $request->get('to_date');
        $params = array_merge($params, $relationalParams);

        return view('purchases.list', [
            'purchaseRecords'   => $purchases,
            'totalAmount'       => $totalAmount,
            'params'            => $params,
            'noOfRecords'       => $noOfRecords,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchases.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        PurchaseRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        MaterialRepository $materialRepo
    ) {
        $saveFlag       = false;
        $errorCode      = 0;
        $wageAmount     = 0;

        $purchaseAccountId  = config('constants.accountConstants.Purchase.id');
        $transactionDate    = Carbon::createFromFormat('d-m-Y', $request->get('purchase_date'))->format('Y-m-d');
        $branchId           = $request->get('branch_id');
        $supplierAccountId  = $request->get('supplier_account_id');
        $materialId         = $request->get('material_id');
        $quantity           = $request->get('purchase_quantity');
        $unitRate           = $request->get('purchase_rate');
        $discount           = $request->get('purchase_discount');
        $totalBill          = $request->get('purchase_total_bill');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming purchase account existency.
            $purchaseAccount = $accountRepo->getAccount($purchaseAccountId);

            //accessing supplier account
            $supplierAccount = $accountRepo->getAccount($supplierAccountId);

            //accessing material account
            $material = $materialRepo->getMaterial($materialId);

            //save purchase to transaction table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_account_id'  => $purchaseAccountId, // debit the purchase account
                'credit_account_id' => $supplierAccountId , // credit the supplier
                'amount'            => $totalBill ,
                'transaction_date'  => $transactionDate,
                'particulars'       => ("Purchase of ". $material->name. " [". $quantity. " x ". $unitRate. " = ". ($quantity * $unitRate). " - ". $discount. " = ". $totalBill. "] Supplier : ". $supplierAccount->name ),
                'branch_id'         => $branchId,
            ]);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            //save to purchase table
            $purchaseResponse = $this->purchaseRepo->savePurchase([
                'transaction_id'    => $transactionResponse['id'],
                'date'              => $transactionDate,
                'material_id'       => $materialId,
                'quantity'          => $quantity,
                'rate'              => $unitRate,
                'discount'          => $discount,
                'total_amount'      => $totalBill,
                'branch_id'         => $branchId,
            ]);

            if(!$purchaseResponse['flag']) {
                throw new AppCustomException("CustomError", $purchaseResponse['errorCode']);
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
            return redirect(route('purchase.index'))->with("message","Purchase details saved successfully. Reference Number : ". $transactionResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to save the purchase details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $purchase   = [];

        try {
            $purchase = $this->purchaseRepo->getPurchase($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Purchase", $errorCode);
        }

        return view('purchases.edit', [
            'purchase' => $purchase,
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
        PurchaseRegistrationRequest $request,
        $id,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo,
        MaterialRepository $materialRepo
    ) {
        $saveFlag       = false;
        $errorCode      = 0;
        $wageAmount     = 0;

        $purchaseAccountId  = config('constants.accountConstants.Purchase.id');
        $transactionDate    = Carbon::createFromFormat('d-m-Y', $request->get('purchase_date'))->format('Y-m-d');
        $branchId           = $request->get('branch_id');
        $supplierAccountId  = $request->get('supplier_account_id');
        $materialId         = $request->get('material_id');
        $quantity           = $request->get('purchase_quantity');
        $unitRate           = $request->get('purchase_rate');
        $discount           = $request->get('purchase_discount');
        $totalBill          = $request->get('purchase_total_bill');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //accessing purchase record
            $purchase = $this->purchaseRepo->getPurchase($id);

            //accessing purchase transaction
            $purchaseTransaction = $transactionRepo->getTransaction($purchase->transaction_id);

            //confirming purchase account existency.
            $purchaseAccount = $accountRepo->getAccount($purchaseAccountId);

            //accessing supplier account
            $supplierAccount = $accountRepo->getAccount($supplierAccountId);

            //accessing material account
            $material = $materialRepo->getMaterial($materialId);

            //save purchase to transaction table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_account_id'  => $purchaseAccountId, // debit the purchase account
                'credit_account_id' => $supplierAccountId , // credit the supplier
                'amount'            => $totalBill ,
                'transaction_date'  => $transactionDate,
                'particulars'       => ("Purchase of ". $material->name. " [". $quantity. " x ". $unitRate. " = ". ($quantity * $unitRate). " - ". $discount. " = ". $totalBill. "] Supplier : ". $supplierAccount->name ),
                'branch_id'         => $branchId,
            ], $purchaseTransaction);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            //save to purchase table
            $purchaseResponse = $this->purchaseRepo->savePurchase([
                'transaction_id'    => $transactionResponse['id'],
                'date'              => $transactionDate,
                'material_id'       => $materialId,
                'quantity'          => $quantity,
                'rate'              => $unitRate,
                'discount'          => $discount,
                'total_amount'      => $totalBill,
                'branch_id'         => $branchId,
            ], $purchase);

            if(!$purchaseResponse['flag']) {
                throw new AppCustomException("CustomError", $purchaseResponse['errorCode']);
            }

            DB::commit();
            $saveFlag = true;
        } catch (Exception $e) {
            //roll back in case of exceptions
            DB::rollback();

            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
        }

        if($saveFlag) {
            return redirect(route('purchase.index'))->with("message","Purchase details updated successfully. Updated Record Number : ". $transactionResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to update the purchase details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
            $deleteResponse = $this->purchaseRepo->deletePurchase($id);
            
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
                $errorCode = 3;
            }
        }

        if($deleteFlag) {
            return redirect(route('purchase.index'))->with("message","Purchase details deleted successfully.")->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to delete the purchase details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
