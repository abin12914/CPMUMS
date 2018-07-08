<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['transaction_date', 'deleted_at'];

    /**
     * Scope a query to only include active transactions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the debit account details associated with a transaction
     */
    public function debitAccount()
    {
        return $this->belongsTo('App\Models\Account','debit_account_id');
    }

    /**
     * Get the credit account details associated with a transaction
     */
    public function creditAccount()
    {
        return $this->belongsTo('App\Models\Account','credit_account_id');
    }

    /**
     * Get the purchase record associated with the transaction.
     */
    public function purchase()
    {
        return $this->hasOne('App\Models\Purchase', 'transaction_id');
    }

    /**
     * Get the sale record associated with the transaction.
     */
    public function sale()
    {
        return $this->hasOne('App\Models\Sale', 'transaction_id');
    }

    /**
     * The transportation that belong to the transaction.
     */
    public function transportation()
    {
        return $this->hasOne('App\Models\Transportation', 'transaction_id');
    }

    /**
     * Get the employee wage record associated with the transaction.
     */
    public function employeeWage()
    {
        return $this->hasOne('App\Models\EmployeeWage', 'transaction_id');
    }

    /**
     * Get the expense record associated with the transaction.
     */
    public function expense()
    {
        return $this->hasOne('App\Models\Expense', 'transaction_id');
    }

    /**
     * Get the voucher record associated with the transaction.
     */
    public function voucher()
    {
        return $this->hasOne('App\Models\Voucher', 'transaction_id');
    }
}
