<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Account;

class VoucherRegistrationRequest extends FormRequest
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
            'voucher_type'          =>  [
                                            'required',
                                            Rule::in([1, 2]),
                                        ],
            'voucher_account_id'    =>  [
                                            'required',
                                            Rule::in(Account::pluck('id')->toArray()),
                                        ],
            'date'                  =>  [
                                            'required',
                                            'date_format:d-m-Y',
                                        ],
            'description'           =>  [
                                            'required',
                                            'max:200',
                                        ],
            'amount'                =>  [
                                            'required',
                                            'numeric',
                                            'min:10',
                                            'max:999999',
                                        ],
        ];
    }
}
