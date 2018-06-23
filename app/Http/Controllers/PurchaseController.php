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
        $this->errorHead            = config('settings.controller_code.Purchase');
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
            [
                'paramName'     => 'date',
                'paramOperator' => '>=',
                'paramValue'    => $fromDate,
            ],
            [
                'paramName'     => 'date',
                'paramOperator' => '<=',
                'paramValue'    => $toDate,
            ],
            [
                'paramName'     => 'branch_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('branch_id'),
            ],
            [
                'paramName'     => 'material_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('material_id'),
            ],
        ];

        $relationalParams = [
            [
                'relation'      => 'transaction',
                'paramName'     => 'credit_account_id',
                'paramValue'    => $request->get('supplier_account_id'),
            ]
        ];

        $purchases = $this->purchaseRepo->getPurchases($params, $relationalParams, $noOfRecords);

        //params passing for auto selection
        $params[0]['paramValue'] = $request->get('from_date');
        $params[1]['paramValue'] = $request->get('to_date');
        $params = array_merge($params, $relationalParams);

        return view('purchases.list', [
            'purchaseRecords'   => $purchases,
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
            return redirect()->back()->with("message","Purchase details saved successfully. Reference Number : ". $purchaseResponse['id'])->with("alert-class", "alert-success");
        }
        
        return redirect()->back()->with("message","Failed to save the purchase details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "alert-danger");
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
        //
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
        //
    }
}
