<?php

namespace App\Listeners;

use App\Events\DeletingEmployeeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingEmployeeEventListener
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
     * @param  DeletingEmployeeEvent  $event
     * @return void
     */
    public function handle(DeletingEmployeeEvent $event)
    {
        if($event->employee->isForceDeleting()) {
            $event->employee->account()->forceDelete();
        } else {
            $event->employee->account()->delete();
        }
    }
}
