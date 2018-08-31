<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Employee;

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
            'branch_id'                 =>  [
                                                'required',
                                                Rule::in(Branch::pluck('id')->toArray()),
                                            ],
            'sale_date'                 =>  [
                                                'required',
                                                'date_format:d-m-Y',
                                            ],
            'customer_account_id'       =>  [
                                                'required',
                                                Rule::in(array_merge(['-1'], Account::pluck('id')->toArray())),
                                            ],
            'customer_name'             =>  [
                                                'required',
                                                'string',
                                                'min:3',
                                                'max:100',
                                            ],
            'customer_phone'            =>  [
                                                'required_if:customer_account_id,-1',
                                                'nullable',
                                                'string',
                                                'min:10',
                                                'max:13',
                                            ],
            'customer_address'          =>  [
                                                'required',
                                                'string',
                                                'min:5',
                                                'max:200',
                                            ],
            'customer_gstin'            =>  [
                                                'nullable',
                                                'string',
                                                'size:15',
                                            ],
            'consignee_name'            =>  [
                                                'nullable',
                                                'string',
                                                'min:3',
                                                'max:100',
                                            ],
            'consignee_address'         =>  [
                                                'required',
                                                'string',
                                                'min:5',
                                                'max:200',
                                            ],
            'consignee_gstin'           =>  [
                                                'nullable',
                                                'string',
                                                'size:15',
                                            ],
            'consignment_charge'        =>  [
                                                'required',
                                                'min:0',
                                                'max:9999',
                                            ],
            'loading_employee_id'       =>  [
                                                'required',
                                                Rule::in(Employee::pluck('id')->toArray()),
                                            ],
            'product_id'                =>  [
                                                'required',
                                                'array',
                                            ],
            'product_id.*'              =>  [
                                                'nullable',
                                                Rule::in(Product::pluck('id')->toArray()),
                                                'distinct'
                                            ],
            'sale_quantity'             =>  [    
                                                'required',
                                                'array',
                                            ],
            'sale_quantity.*'           =>  [    
                                                'required',
                                                'integer',
                                                'min:1',
                                                'max:99999'
                                            ],
            'sale_rate'                 =>  [
                                                'required',
                                                'array',
                                            ],
            'sale_rate.*'               =>  [
                                                'required',
                                                'numeric',
                                                'min:0.1',
                                                'max:99999'
                                            ],
            'sub_bill'                  =>  [
                                                'required',
                                                'array',
                                            ],
            'sub_bill.*'                =>  [
                                                'nullable',
                                                'numeric',
                                                'min:0.1',
                                                'max:99999'
                                            ],
            'total_amount'              =>  [
                                                'required',
                                                'numeric',
                                                'max:99999',
                                                'min:1'
                                            ],
            'discount'                  =>  [
                                                'required',
                                                'numeric',
                                                'max:9999',
                                                'min:0'
                                            ],
            'total_bill'                =>  [
                                                'required',
                                                'numeric',
                                                'max:99999',
                                                'min:1'
                                            ],
            'tax_invoice__flag'         =>  [
                                                'nullable',
                                                'boolean'
                                            ],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->checkCalculations()) {
                $validator->errors()->add('calculations', 'Something went wrong with the calculations! Please try again after reloading the page');
            }
        });
    }

    public function checkCalculations() {
        $totalAmount        = 0;
        $productEmptyCount  = 0;

        foreach ($this->request->get("product_id") as $index => $productId) {
            if(empty($productId)) {
                $productEmptyCount ++;
                continue;
            }

            if(empty($this->request->get('sale_quantity')[$index]) || empty($this->request->get('sale_rate')[$index]) || empty($this->request->get('sub_bill')[$index])) {
                return false;
            }
            
            $subTotal = $this->request->get('sale_quantity')[$index] * $this->request->get('sale_rate')[$index];
            
            if($subTotal != $this->request->get('sub_bill')[$index]) {
                return false;
            }
            $totalAmount = $totalAmount + $subTotal;
        }

        if($productEmptyCount > 1) {
            return false;
        }

        $billTotal  = $this->request->get("total_amount");
        $discount   = $this->request->get("discount");
        $billFinal  = $this->request->get("total_bill");

        if(($billTotal != $totalAmount) || (($billTotal - $discount) != $billFinal)) {
            return false;
        }
        
        return true;
    }
}
