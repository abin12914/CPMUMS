<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ExpenseRepository;
use App\Http\Requests\ExpenseRegistrationRequest;
use App\Http\Requests\ExpenseFilterRequest;
use \Carbon\Carbon;

class ExpenseController extends Controller
{
    protected $expenseRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

     public function __construct(ExpenseRepository $expenseRepo)
    {
        $this->expenseRepo          = $expenseRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.error_heads.Expense');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExpenseFilterRequest $request)
    {
        $fromDate           = !empty($request->get('from_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('from_date'))->format('Y-m-d') : "";
        $toDate             = !empty($request->get('to_date')) ? Carbon::createFromFormat('d-m-Y', $request->get('to_date'))->format('Y-m-d') : "";
        $noOfRecords        = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

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
                    'paramName'     => 'truck_id',
                    'paramOperator' => '=',
                    'paramValue'    => $request->get('truck_id'),
                ],
                [
                    'paramName'     => 'service_id',
                    'paramOperator' => '=',
                    'paramValue'    => $request->get('service_id'),
                ],
            ];

        $relationalParams = [
                [
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
                'services'      => $this->expenseRepo->getServices(),
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
        return view('expenses.register', [
                'services'  => $this->expenseRepo->getServices(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseRegistrationRequest $request)
    {
        $response   = $this->expenseRepo->saveExpense($request);

        if($response['flag']) {
            return redirect()->back()->with("message","Expense details saved successfully. Reference Number : ". $response['id'])->with("alert-class", "alert-success");
        }
        
        return redirect()->back()->with("message","Failed to save the expense details. Error Code : ". $response['errorCode'])->with("alert-class", "alert-danger");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('expenses.details', [
                'expense' => $this->expenseRepo->getExpense($id),
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
        $deleteFlag = $this->expenseRepo->deleteExpense($id);

        if($deleteFlag['flag']) {
            return redirect(route('expenses.index'))->with("message", "Expense details deleted successfully.")->with("alert-class", "alert-success");
        }

        return redirect(route('expenses.index'))->with("message", "Deletion failed. Error Code : ". $deleteFlag['errorCode'])->with("alert-class", "alert-danger");
    }
}
