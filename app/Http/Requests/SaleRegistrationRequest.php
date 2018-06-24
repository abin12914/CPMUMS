<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Product;

class SaleRegistrationRequest extends FormRequest
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
            'branch_id'             => [
                                            'required',
                                            Rule::in(Branch::pluck('id')->toArray()),
                                        ],
            'sale_date'             => [
                                            'required',
                                            'date_format:d-m-Y',
                                        ],
            'sale_type'             => [
                                            'required',
                                            Rule::in([1,2])
                                        ],
            'customer_account_id'   => [
                                            'required_if:sale_type,1',
                                            'integer',
                                            Rule::in(Account::pluck('id')->toArray()),
                                        ],
            'name'                  => [
                                            'required_if:sale_type,2',
                                            'max:100'
                                        ],
            'phone'                 => [
                                            'required_if:sale_type,2',
                                            'integer',
                                            'max:12',
                                            'min:10'
                                        ],
            'product_id'            => [
                                            'required',
                                            'array'
                                        ],
            'product_id.*'          => [
                                            'nullable',
                                            Rule::in(Product::pluck('id')->toArray()),
                                        ],
            'sale_quantity'         => [    
                                            'required',
                                            'array'
                                        ],
            'sale_quantity.*'       => [    
                                            'required',
                                            'integer',
                                            'min:1',
                                            'max:99999'
                                        ],
            'sale_rate'             => [
                                            'required',
                                            'array'
                                        ],
            'sale_rate.*'           => [
                                            'required',
                                            'numeric',
                                            'min:0.1',
                                            'max:99999'
                                        ],
            'sub_bill'              => [
                                            'required',
                                            'array'
                                        ],
            'sub_bill.*'            => [
                                            'nullable',
                                            'numeric',
                                            'min:0.1',
                                            'max:99999'
                                        ],
            'total_amount'          => [
                                            'required',
                                            'numeric',
                                            'max:99999',
                                            'min:1'
                                        ],
            'discount'              => [
                                            'required',
                                            'numeric',
                                            'max:9999',
                                            'min:0'
                                        ],
            'total_bill'            => [
                                            'required',
                                            'numeric',
                                            'max:99999',
                                            'min:1'
                                        ],
        ];
    }
}
