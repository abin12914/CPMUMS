<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRegistrationRequest extends FormRequest
{
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
                                        Rule::unique('accounts')->ignore($this->id),
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
                                        Rule::unique('accounts')->ignore($this->id),
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
