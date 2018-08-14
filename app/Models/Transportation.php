<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\DeletingTransportationEvent;

class Transportation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public $timestamps = false;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleting' => DeletingTransportationEvent::class,
    ];

    /**
     * Scope a query to only include active purchase.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
    /**
     * Get the transaction details associated with the purchase
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }

    /**
     * Get the transaction details associated with the loading charge.
     */
    public function LoadingChargetransaction()
    {
        return $this->belongsTo('App\Models\Transaction','loading_charge_transaction_id');
    }

    /**
     * Get the branch details associated with the purchase
     */
    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'sale_id');
    }
}
