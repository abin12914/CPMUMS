<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Branch;
use App\Models\Service;
use App\Models\Account;

class ExpenseRegistrationRequest extends FormRequest
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
            'date'                  =>  [
                                            'required',
                                            'date_format:d-m-Y',
                                        ],
            'service_id'            =>  [
                                            'required',
                                            Rule::in(Service::pluck('id')->toArray()),
                                        ],
            'supplier_account_id'   =>  [
                                            'required',
                                            Rule::in(Account::pluck('id')->toArray()),
                                        ],
            'description'           =>  [
                                            'required',
                                            'min:5',
                                            'max:200',
                                        ],
            'bill_amount'           =>  [
                                            'required',
                                            'numeric',
                                            'min:10',
                                            'max:999999',
                                        ],
        ];
    }
}
