<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRegistrationRequest extends FormRequest
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
            'product_name'              =>  [
                                                'required',
                                                'max:100',
                                                Rule::unique('products', 'name')->ignore($this->product),
                                            ],
            'hsn_code'                  =>  [
                                                'nullable',
                                                'min:4',
                                                'max:10',
                                                'alpha_num',
                                            ],
            'uom_code'                  =>  [
                                                'required',
                                                'size:3',
                                                'alpha',
                                            ],
            'description'               =>  [
                                                'required',
                                                'max:200',
                                            ],
            'rate'                      =>  [
                                                'required',
                                                'numeric',
                                                'min:0.1',
                                                'max:9999'
                                            ],
            'loading_charge_per_piece'  =>  [
                                                'required',
                                                'numeric',
                                                'min:0.1',
                                                'max:999',
                                            ],
        ];
    }
}
