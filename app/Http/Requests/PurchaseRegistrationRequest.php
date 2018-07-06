<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Material;

class PurchaseRegistrationRequest extends FormRequest
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
            'branch_id'             =>  [
                                            'required',
                                            Rule::in(Branch::pluck('id')->toArray()),
                                        ],
            'purchase_date'         =>  [
                                            'required',
                                            'date_format:d-m-Y',
                                        ],
            'supplier_account_id'   =>  [
                                            'required',
                                            Rule::in(Account::pluck('id')->toArray()),
                                        ],
            'material_id'           =>  [
                                            'required',
                                            Rule::in(Material::pluck('id')->toArray()),
                                        ],
            'purchase_quantity'     =>  [
                                            'required',
                                            'numeric',
                                            'min:1',
                                            'max:1000',
                                        ],
            'purchase_rate'         =>  [
                                            'required',
                                            'numeric',
                                            'min:0.1',
                                            'max:50000',
                                        ],
            'purchase_bill'         =>  [
                                            'required',
                                            'numeric',
                                            'max:50000',
                                            'min:10',
                                        ],
            'purchase_discount'     =>  [
                                            'required',
                                            'numeric',
                                            'max:1000',
                                            'min:0',
                                        ],
            'purchase_total_bill'   =>  [
                                            'required',
                                            'numeric',
                                            'max:999999',
                                            'min:10',
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
        $quanty     = $this->request->get("purchase_quantity");
        $rate       = $this->request->get("purchase_rate");
        $bill       = $this->request->get("purchase_bill");
        $discount   = $this->request->get("purchase_discount");
        $totalBill  = $this->request->get("purchase_total_bill");

        if((($quanty * $rate) == $bill) && ($bill - $discount) == $totalBill) {
            return true;
        }
        return false;
    }
}
