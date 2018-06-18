<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ProductionRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\AccountRepository;
use App\Repositories\EmployeeWageRepository;
use App\Repositories\TransactionRepository;
use App\Http\Requests\ProductionRegistrationRequest;
use App\Http\Requests\ProductionFilterRequest;
use \Carbon\Carbon;
use DB;
use Exception;
use App\Exceptions\AppCustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductionController extends Controller
{
    protected $productionRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(ProductionRepository $productionRepo)
    {
        $this->productionRepo       = $productionRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.controller_code.Production');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductionFilterRequest $request)
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
                'paramName'     => 'product_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('product_id'),
            ],
            [
                'paramName'     => 'employee_id',
                'paramOperator' => '=',
                'paramValue'    => $request->get('employee_id'),
            ],
        ];

        return view('production.list', [
                'production'   => $this->accountRepo->getAccounts($params, $noOfRecords),
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
        return view('production.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        ProductionRegistrationRequest $request,
        AccountRepository $accountRepo,
        EmployeeRepository $employeeRepo,
        EmployeeWageRepository $employeeWageRepo,
        TransactionRepository $transactionRepo
    ) {
        $saveFlag       = false;
        $errorCode      = 0;
        $wageAmount     = 0;

        $employeeWageAccountId = config('constants.accountConstants.EmployeeWage.id');

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming wage account existency.
            $employeeWageAccount = $accountRepo->getAccount($employeeWageAccountId);

            //employee record
            $employee = $employeeRepo->getEmployee($request->get('employee_id'));

            //save to account table
            $productionResponse   = $this->productionRepo->saveAccount([
                'date'              => $request->get('date'),
                'branch_id'         => $request->get('branch_id'),
                'employee_id'       => $request->get('employee_id'),
                'product_id'        => $request->get('product_id'),
                'mould_quantity'    => $request->get('mould_quantity'),
                'piece_quantity'    => $request->get('piece_quantity'),
            ]);

            if($productionResponse['flag']) {
                if($employee->wage_type == 3) {
                    $wageAmount = $request->get('piece_quantity') * $employee->wage_rate;
                }
            }
            else {
                throw new AppCustomException("CustomError", $productionResponse['errorCode']);
            }

            //save employee wage to transaction table
            $transactionResponse   = $employeeWageRepo->saveTransaction([
                'debit_account_id'  => $employeeWageAccount, // debit the employee wage account
                'credit_account_id' => $employee->account->id, // credit the employee
                'amount'            => $openingBalance,
                'transaction_date'  => Carbon::now()->format('Y-m-d'),
                'particulars'       => $particulars,
                'branch_id'         => 0,
            ]);

            if(!$transactionResponse['flag']) {
                throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
            }

            //save to employee wage table
            $wageResponse   = $employeeWageRepo->saveEmployeeWage([
                'production_id'     => $productionResponse['id'],
                'transaction_id'    => $transactionResponse['id'],
                'from_date'         => $request->get('date'),
                'to_date'           => null,
                'wage_type'         => 3, //per piece wage
                'wage_amount'       => $wageAmount,
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
