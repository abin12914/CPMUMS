<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Employee;

class EmployeeRegistrationRequest extends FormRequest
{
    public $accountId = '';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $wageTypes  = config('constants.employeeWageTypes');
        if(!empty($this->employee)) {
            $employee = Employee::find($this->employee);

            if(!empty($employee) && !empty($employee->id)) {
                $this->accountId = $employee->account_id;
            }
        }

        return [
            'name'              =>  [
                                        'required',
                                        'min:4',
                                        'max:200',
                                    ],
            'phone'             =>  [
                                        'required',
                                        'numeric',
                                        'digits_between:10,13',
                                        Rule::unique('accounts')->ignore($this->accountId),
                                    ],
            'address'           =>  [
                                        'nullable',
                                        'max:200',
                                    ],
            'image_file'        =>  [
                                        'nullable',
                                        'mimetypes:image/jpeg,image/jpg,image/bmp,image/png',
                                        'max:3000',
                                    ],
            'wage_type'         =>  [
                                        'required',
                                        Rule::in(array_keys($wageTypes)),
                                    ],
            'wage'              =>  [
                                        'required',
                                        'numeric',
                                        'min:0',
                                        'max:99999',
                                    ],
            'account_name'      =>  [
                                        'required',
                                        'max:200',
                                        Rule::unique('accounts')->ignore($this->accountId),
                                    ],
            'financial_status'  =>  [
                                        'required',
                                        Rule::in([0, 1, 2])
                                    ],
            'opening_balance'   =>  [
                                        'required',
                                        'numeric',
                                        'min:0',
                                        'max:999999'
                                    ]
        ];
    }
}
