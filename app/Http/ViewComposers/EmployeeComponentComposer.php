<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\EmployeeRepository;
use Exception;
//use App\Exceptions\AppCustomException;

class EmployeeComponentComposer
{
    protected $employees = [], $errorHead = null;

    /**
     * Create a new employees partial composer.
     *
     * @param  EmployeeRepository  $employees
     * @return void
     */
    public function __construct(EmployeeRepository $employeeRepo)
    {
        $errorCode          = 0;
        $this->errorHead    = config('settings.composer_code.EmployeeComponentComposer');

        try {
            $this->employees = $employeeRepo->getEmployees();
        } catch (Exception $e) {
            if($e->getMessage() == "CustomError") {
                $errorCode = $e->getCode();
            } else {
                $errorCode = 1;
            }
            
            //throw new AppCustomException("CustomError", ($this->errorHead + $errorCode));
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['employeesCombo' => $this->employees]);
    }
}