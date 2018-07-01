<?php

namespace App\Listeners;

use App\Events\DeletingProductionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingProductionEventListener
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
     * @param  DeletingProductionEvent  $event
     * @return void
     */
    public function handle(DeletingProductionEvent $event)
    {
        if($event->production->isForceDeleting()) {
            $event->production->employeeWage()->forceDelete();
        } else {
            $event->production->employeeWage()->delete();
        }
    }
}
