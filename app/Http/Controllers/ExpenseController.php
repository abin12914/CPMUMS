<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ExpenseRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\AccountRepository;
use App\Http\Requests\ExpenseRegistrationRequest;
use App\Http\Requests\ExpenseFilterRequest;
use Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExpenseController extends Controller
{
    protected $expenseRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(ExpenseRepository $expenseRepo)
    {
        $this->expenseRepo          = $expenseRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.ExpenseController');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExpenseFilterRequest $request)
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
            'service_id'    =>  [
                                    'paramName'     => 'service_id',
                                    'paramOperator' => '=',
                                    'paramValue'    => $request->get('service_id'),
                                ],
        ];

        $relationalParams = [
            'supplier_account_id'   =>  [
                                            'relation'      => 'transaction',
                                            'paramName'     => 'credit_account_id',
                                            'paramValue'    => $request->get('supplier_account_id'),
                                        ]
        ];

        $expenses = $this->expenseRepo->getExpenses($params, $relationalParams, $noOfRecords);

        //params passing for auto selection
        $params[0]['paramValue'] = $request->get('from_date');
        $params[1]['paramValue'] = $request->get('to_date');
        $params = array_merge($params, $relationalParams);
        
        return view('expenses.list', [
            'expenses'      => $expenses,
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
        return view('expenses.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        ExpenseRegistrationRequest $request,
        TransactionRepository $transactionRepo,
        AccountRepository $accountRepo
    ) {
        $saveFlag   = false;
        $errorCode  = 0;

        $expenseAccountId   = config('constants.accountConstants.ServiceAndExpense.id');
        $transactionDate    = Carbon::createFromFormat('d-m-Y', $request->get('date'))->format('Y-m-d');
        $branchId           = $request->get('branch_id');
        $totalBill          = $request->get('bill_amount');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming expense account exist-ency.
            $expenseAccount = $accountRepo->getAccount($expenseAccountId);

            //save expense transaction to table
            $transactionResponse   = $transactionRepo->saveTransaction([
                'debit_account_id'  => $expenseAccountId, // debit the expense account
                'credit_account_id' => $request->get('supplier_account_id'), // credit the supplier
                'amount'            => $totalBill ,
                'transaction_date'  => $transactionDate,
                'particulars'       => $request->get('description')."[Purchase & Expense]",
                'branch_id'         => $branchId,
            ]);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            //save to expense table
            $expenseResponse = $this->expenseRepo->saveExpense([
                'transaction_id' => $transactionResponse['id'],
                'date'           => $transactionDate,
                'service_id'     => $request->get('service_id'),
                'bill_amount'    => $totalBill,
                'branch_id'      => $branchId,
            ]);

            if(!$expenseResponse['flag']) {
                throw new AppCustomException("CustomError", $expenseResponse['errorCode']);
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
            return redirect()->back()->with("message","Expense details saved successfully. Reference Number : ". $transactionResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to save the expense details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
        $expense    = [];

        try {
            $expense = $this->expenseRepo->getExpense($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Expense", $errorCode);
        }

        return view('expenses.details', [
            'expense' => $expense,
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
        return redirect()->back()->with("message", "Editing temporarily unavailable.")->with("alert-class", "warning");
        /*$errorCode  = 0;
        $expense    = [];

        try {
            $expense = $this->expenseRepo->getExpense($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Expense", $errorCode);
        }

        return view('expense.edit', [
            'expense' => $expense,
        ]);*/
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
        return redirect()->back()->with("message", "Deletion temporarily restricted.")->with("alert-class", "warning");
    }
}
