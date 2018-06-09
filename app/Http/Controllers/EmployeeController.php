<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\EmployeeRepository;
use App\Http\Requests\EmployeeRegistrationRequest;
use App\Http\Requests\EmployeeFilterRequest;

class EmployeeController extends Controller
{
    protected $employeeRepo;
    public $errorHead = null, $noOfRecordsPerPage = null;

    public function __construct(EmployeeRepository $employeeRepo)
    {
        $this->employeeRepo         = $employeeRepo;
        $this->noOfRecordsPerPage   = config('settings.no_of_record_per_page');
        $this->errorHead            = config('settings.error_heads.Account');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmployeeFilterRequest $request)
    {
        $noOfRecords    = !empty($request->get('no_of_records')) ? $request->get('no_of_records') : $this->noOfRecordsPerPage;

        $params = [
                'wage_type' => $request->get('wage_type'),
                'id'        => $request->get('employee_id'),
            ];
        
        return view('employees.list', [
                'employeesCombo'    => $this->employeeRepo->getEmployees(),
                'employees'         => $this->employeeRepo->getEmployees($params, $noOfRecords),
                'wageTypes'         => config('constants.employeeWageTypes'),
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
        return view('employees.register', [
                'wageTypes' => config('constants.employeeWageTypes'),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRegistrationRequest $request)
    {
        $response   = $this->employeeRepo->saveEmployee($request);

        if($response['flag']) {
            return redirect()->back()->with("message","Employee details saved successfully. Reference Number : ". $response['id'])->with("alert-class", "alert-success");
        }
        
        return redirect()->back()->with("message","Failed to save the employee details. Error Code : ". $response['errorCode'])->with("alert-class", "alert-danger");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('employees.details', [
                'employee'  => $this->employeeRepo->getEmployee($id),
                'wageTypes' => config('constants.employeeWageTypes'),
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
        $deleteFlag = $this->employeeRepo->deleteEmployee($id);

        if($deleteFlag['flag']) {
            return redirect(route('employees.index'))->with("message", "Employee details deleted successfully.")->with("alert-class", "alert-success");
        }

        return redirect(route('employees.index'))->with("message", "Deletion failed. Error Code : ". $deleteFlag['errorCode'])->with("alert-class", "alert-danger");
    }
}
