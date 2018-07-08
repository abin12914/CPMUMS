<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\DeletingEmployeeWageEvent;

class EmployeeWage extends Model
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
        'deleting' => DeletingEmployeeWageEvent::class,
    ];
    
    /**
     * Scope a query to only include active employees.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get the production details related to the employee
     */
    public function production()
    {
        return $this->belongsTo('App\Models\Production');
    }

    /**
     * Get the transaction details related to the employee wage
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction');
    }
}
