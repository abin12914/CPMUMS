<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\DeletingSaleEvent;

class Sale extends Model
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
        'deleting' => DeletingSaleEvent::class,
    ];

    /**
     * Scope a query to only include active sale.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
    /**
     * Get the transaction details associated with the sale
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
     * The sales that belong to the products.
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'sale_product')->as('saleDetail')->withPivot('quantity', 'rate');
    }

    /**
     * The transportation that belong to the sale.
     */
    public function transportation()
    {
        return $this->hasOne('App\Models\Transportation', 'sale_id');
    }
}
