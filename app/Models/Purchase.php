<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\DeletingPurchaseEvent;

class Purchase extends Model
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
        'deleting' => DeletingPurchaseEvent::class,
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
     * Get the branch details associated with the purchase
     */
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch','branch_id');
    }

    /**
     * Get the material details associated with the purchase
     */
    public function material()
    {
        return $this->belongsTo('App\Models\Material','material_id');
    }
}
