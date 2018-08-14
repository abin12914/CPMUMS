<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRegistrationRequest extends FormRequest
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
        $relationTypes  = config('constants.accountRelationTypes');
        
        return [
            'account_name'          =>  [
                                            'required',
                                            'max:100',
                                            Rule::unique('accounts')->ignore($this->account),
                                        ],
            'description'           =>  [
                                            'nullable',
                                            'max:200',
                                        ],
            'financial_status'      =>  [
                                            'required',
                                            Rule::in([0, 1, 2]),
                                        ],
            'opening_balance'       =>  [
                                            'required',
                                            'numeric',
                                            'min:0',
                                            'max:9999999',
                                        ],
            'name'                  =>  [
                                            'required',
                                            'max:100',
                                        ],
            'phone'                 =>  [
                                            'required',
                                            'numeric',
                                            'digits_between:10,13',
                                            Rule::unique('accounts')->ignore($this->account),
                                        ],
            'address'               =>  [
                                            'nullable',
                                            'max:200',
                                        ],
            'image_file'            =>  [
                                            'nullable',
                                            'mimetypes:image/jpeg,image/jpg,image/bmp,image/png',
                                            'max:3000',
                                        ],
            'relation_type'         =>  [
                                            'required',
                                            Rule::in(array_keys($relationTypes)),
                                        ],
            'gstin'                 =>  [
                                            'nullable',
                                            'string',
                                            'size:15',
                                        ]
        ];
    }
}
