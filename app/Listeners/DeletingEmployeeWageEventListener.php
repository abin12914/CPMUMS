<?php

namespace App\Listeners;

use App\Events\DeletingEmployeeWageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingEmployeeWageEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DeletingEmployeeWageEvent  $event
     * @return void
     */
    public function handle(DeletingEmployeeWageEvent $event)
    {
        $transaction = $event->employeeWage->transaction()->firstOrFail();

        if($event->employeeWage->isForceDeleting()) {
            $transaction->forceDelete();
        } else {
            $transaction->delete();
        }
    }
}
