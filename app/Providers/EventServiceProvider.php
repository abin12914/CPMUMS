<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\DeletingEmployeeWageEvent' => [
            'App\Listeners\DeletingEmployeeWageEventListener',
        ],
        'App\Events\DeletingExpenseEvent' => [
            'App\Listeners\DeletingExpenseEventListener',
        ],
        'App\Events\DeletingProductionEvent' => [
            'App\Listeners\DeletingProductionEventListener',
        ],
        'App\Events\DeletingPurchaseEvent' => [
            'App\Listeners\DeletingPurchaseEventListener',
        ],
        'App\Events\DeletingSaleEvent' => [
            'App\Listeners\DeletingSaleEventListener',
        ],
        'App\Events\DeletingVoucherEvent' => [
            'App\Listeners\DeletingVoucherEventListener',
        ],
        'App\Events\DeletingTransportationEvent' => [
            'App\Listeners\DeletingTransportationEventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
