<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRegistrationRequest extends FormRequest
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
        return [
            'branch_name'   =>  [
                                    'required',
                                    'max:100',
                                    //Rule::unique('branches', 'name')->ignore($this->branch),
                                ],
            'place'         =>  [
                                    'required',
                                    'max:200',
                                ],
            'address'       =>  [
                                    'required',
                                    'max:200',
                                ],
            'gstin'         =>  [
                                    'required',
                                    'size:15',
                                    //Rule::unique('branches')->ignore($this->branch),
                                ],
            'primary_phone' =>  [
                                    'required',
                                    'min:10',
                                    'max:13',
                                    //Rule::unique('branches')->ignore($this->branch),
                                    //Rule::unique('branches', 'secondary_phone'),
                                ],
            'secondary_phone'=>  [
                                    'nullable',
                                    'min:10',
                                    'max:13',
                                    //Rule::unique('branches', 'primary_phone'),
                                    //Rule::unique('branches')->ignore($this->branch),
                                ],
        ];
    }
}
