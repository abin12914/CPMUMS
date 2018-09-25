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
use Carbon\Carbon;
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
        $this->errorHead            = config('settings.controller_code.ProductionController');
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
            'product_id'    =>  [
                                    'paramName'     => 'product_id',
                                    'paramOperator' => '=',
                                    'paramValue'    => $request->get('product_id'),
                                ],
            'employee_id'   =>  [
                                    'paramName'     => 'employee_id',
                                    'paramOperator' => '=',
                                    'paramValue'    => $request->get('employee_id'),
                                ],
        ];

        $productionRecords  = $this->productionRepo->getProductions($params, $noOfRecords);
        $productions        = $this->productionRepo->getProductions($params, null);
        $noOfMoulds         = $productions->sum('mould_quantity');
        $noOfPieces         = $productions->sum('piece_quantity');

        //params passing for auto selection
        $params['from_date']['paramValue'] = $request->get('from_date');
        $params['to_date']['paramValue'] = $request->get('to_date');

        return view('production.list', [
            'productionRecords' => $productionRecords,
            'noOfMoulds'        => $noOfMoulds,
            'noOfPieces'        => $noOfPieces,
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

        $employeeWageAccountId  = config('constants.accountConstants.EmployeeWage.id');
        $transactionDate        = !empty($request->get('date')) ? Carbon::createFromFormat('d-m-Y', $request->get('date'))->format('Y-m-d') : "";

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //confirming wage account existency.
            $employeeWageAccount = $accountRepo->getAccount($employeeWageAccountId);

            //employee record
            $employee = $employeeRepo->getEmployee($request->get('employee_id'));

            //save to account table
            $productionResponse   = $this->productionRepo->saveProduction([
                'date'              => $transactionDate,
                'branch_id'         => $request->get('branch_id'),
                'employee_id'       => $request->get('employee_id'),
                'product_id'        => $request->get('product_id'),
                'mould_quantity'    => $request->get('mould_quantity'),
                'piece_quantity'    => $request->get('piece_quantity'),
            ]);

            if(!$productionResponse['flag']) {
                throw new AppCustomException("CustomError", $productionResponse['errorCode']);
            }

            //if per piece wage for the employee
            if($employee->wage_type == 3) {
                $wageAmount = $request->get('mould_quantity') * $employee->wage_rate;

                //save employee wage to transaction table
                $transactionResponse   = $transactionRepo->saveTransaction([
                    'debit_account_id'  => $employeeWageAccountId, // debit the employee wage account
                    'credit_account_id' => $employee->account->id, // credit the employee
                    'amount'            => $wageAmount,
                    'transaction_date'  => $transactionDate,
                    'particulars'       => ("Wage generated for ". $employee->account->name . " [No Of Piece : ". $request->get('mould_quantity'). " x  Wage Rate :". $employee->wage_rate. "]"),
                    'branch_id'         => 0,
                ]);

                if(!$transactionResponse['flag']) {
                    throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
                }

                //save to employee wage table
                $wageResponse   = $employeeWageRepo->saveEmployeeWage([
                    'production_id'     => $productionResponse['id'],
                    'transaction_id'    => $transactionResponse['id'],
                    'from_date'         => $transactionDate,
                    'to_date'           => null,
                    'wage_type'         => 3, //per piece wage
                    'wage_amount'       => $wageAmount,
                ]);
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
            return redirect(route('production.index'))->with("message","Production details saved successfully. Reference Number : ". $productionResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to save the production details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
        $production = [];

        try {
            $production = $this->productionRepo->getProduction($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 2;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Production", $errorCode);
        }

        return view('production.details', [
            'production' => $production,
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
        $production = [];

        try {
            $production = $this->productionRepo->getProduction($id);
        } catch (\Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 3;
            }
            //throwing methodnotfound exception when no model is fetched
            throw new ModelNotFoundException("Production", $errorCode);
        }

        return view('production.edit', [
            'production' => $production,
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
        ProductionRegistrationRequest $request,
        $id,
        AccountRepository $accountRepo,
        EmployeeRepository $employeeRepo,
        EmployeeWageRepository $employeeWageRepo,
        TransactionRepository $transactionRepo
    ) {
        $saveFlag                = false;
        $errorCode               = 0;
        $wageAmount              = 0;
        $employeeWageTransaction = null;

        $employeeWageAccountId  = config('constants.accountConstants.EmployeeWage.id');
        $transactionDate        = !empty($request->get('date')) ? Carbon::createFromFormat('d-m-Y', $request->get('date'))->format('Y-m-d') : "";

        //wrappin db transactions
        DB::beginTransaction();
        try {
            //get the production record
            $production = $this->productionRepo->getProduction($id);

            //get employee wage if any
            $employeeWage = $employeeWageRepo->getEmployeeWages(['production_id' => $production->id])->first();
            if(!empty($employeeWage)) {
                $employeeWageTransaction = $transactionRepo->getTransaction($employeeWage->transaction_id);
            }

            //confirming wage account existency.
            $employeeWageAccount = $accountRepo->getAccount($employeeWageAccountId);

            //employee record
            $employee = $employeeRepo->getEmployee($request->get('employee_id'));

            //save to account table
            $productionResponse   = $this->productionRepo->saveProduction([
                'date'              => $transactionDate,
                'branch_id'         => $request->get('branch_id'),
                'employee_id'       => $request->get('employee_id'),
                'product_id'        => $request->get('product_id'),
                'mould_quantity'    => $request->get('mould_quantity'),
                'piece_quantity'    => $request->get('piece_quantity'),
            ], $production);

            if(!$productionResponse['flag']) {
                throw new AppCustomException("CustomError", $productionResponse['errorCode']);
            }

            //if per piece wage for the employee
            if($employee->wage_type == 3) {
                $wageAmount = $request->get('mould_quantity') * $employee->wage_rate;

                //save employee wage to transaction table
                $transactionResponse   = $transactionRepo->saveTransaction([
                    'debit_account_id'  => $employeeWageAccountId, // debit the employee wage account
                    'credit_account_id' => $employee->account->id, // credit the employee
                    'amount'            => $wageAmount,
                    'transaction_date'  => $transactionDate,
                    'particulars'       => ("Wage generated for ". $employee->account->name . " [No Of Piece : ". $request->get('mould_quantity'). " x  Wage Rate :". $employee->wage_rate. "]"),
                    'branch_id'         => 0,
                ], $employeeWageTransaction);

                if(!$transactionResponse['flag']) {
                    throw new AppCustomException("CustomError", $transactionResponse['errorCode']);
                }

                //save to employee wage table
                $wageResponse   = $employeeWageRepo->saveEmployeeWage([
                    'production_id'     => $productionResponse['id'],
                    'transaction_id'    => $transactionResponse['id'],
                    'from_date'         => $transactionDate,
                    'to_date'           => null,
                    'wage_type'         => 3, //per piece wage
                    'wage_amount'       => $wageAmount,
                ], $employeeWage);
            } else {
                if(!empty($employeeWage)) {
                    $employeeWageDeleteResponse = $employeeWageRepo->deleteEmployeeWage($employeeWage->id);
                }
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
            return redirect(route('production.index'))->with("message","Production details saved successfully. Reference Number : ". $productionResponse['id'])->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to save the production details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
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
            $deleteResponse = $this->productionRepo->deleteProduction($id);
            
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
            return redirect(route('production.index'))->with("message","Production details deleted successfully.")->with("alert-class", "success");
        }
        
        return redirect()->back()->with("message","Failed to delete the production details. Error Code : ". $this->errorHead. "/". $errorCode)->with("alert-class", "error");
    }
}
