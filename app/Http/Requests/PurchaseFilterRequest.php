<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Branch;
use App\Models\Material;
use App\Models\Account;

class PurchaseFilterRequest extends FormRequest
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
            'from_date'             =>  [
                                            'nullable',
                                            'date_format:d-m-Y',
                                        ],
            'to_date'               =>  [
                                            'nullable',
                                            'date_format:d-m-Y',
                                        ],
            'branch_id'             =>  [
                                            'nullable',
                                            Rule::in(Branch::pluck('id')->toArray()),
                                        ],
            'material_id'           =>  [
                                            'nullable',
                                            Rule::in(Material::pluck('id')->toArray()),
                                        ],
            'supplier_account_id'   =>  [
                                            'nullable',
                                            Rule::in(Account::pluck('id')->toArray()),
                                        ],
            'no_of_records'         =>  [
                                            'nullable',
                                            'min:2',
                                            'max:100',
                                            'integer',
                                        ],
            'page'                  =>  [
                                            'nullable',
                                            'integer',
                                        ],
        ];
    }
}
