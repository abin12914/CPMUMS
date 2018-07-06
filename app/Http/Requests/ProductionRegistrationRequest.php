<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Product;

class ProductionRegistrationRequest extends FormRequest
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
            'branch_id'         =>  [
                                        'required',
                                        Rule::in(Branch::pluck('id')->toArray()),
                                    ],
            'date'              =>  [
                                        'required',
                                        'date_format:d-m-Y',
                                    ],
            'product_id'        =>  [
                                        'required',
                                        Rule::in(Product::pluck('id')->toArray()),
                                    ],
            'employee_id'       =>  [
                                        'required',
                                        Rule::in(Employee::pluck('id')->toArray()),
                                    ],
            'mould_quantity'    =>  [
                                        'required',
                                        'numeric',
                                        'min:1',
                                        'max:999',
                                    ],
            'piece_quantity'    =>  [
                                        'required',
                                        'numeric',
                                        'min:1',
                                        'max:9999',
                                    ],
        ];
    }
}
