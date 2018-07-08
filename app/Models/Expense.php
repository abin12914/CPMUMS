<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\DeletingExpenseEvent;

class Expense extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date', 'deleted_at'];

    public $timestamps = false;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleting' => DeletingExpenseEvent::class,
    ];

    /**
     * Scope a query to only include active expenses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the branch details associated with the purchase
     */
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch','branch_id');
    }
    
    /**
     * Get the transaction details associated with the expense
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }

    /**
     * Get the service details associated with the expense
     */
    public function service()
    {
        return $this->belongsTo('App\Models\Service','service_id');
    }
}
