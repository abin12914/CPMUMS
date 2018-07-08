<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\DeletingProductionEvent;

class Production extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'production';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'date'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleting' => DeletingProductionEvent::class,
    ];

    /**
     * Scope a query to only include active accounts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the branch details associated with the production
     */
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch','branch_id');
    }

    /**
     * Get the employee details associated with the production
     */
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee','employee_id');
    }

    /**
     * Get the employee wage record associated with the production.
     */
    public function employeeWage()
    {
        return $this->hasOne('App\Models\EmployeeWage', 'production_id');
    }

    /**
     * Get the product details associated with the production
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
}
