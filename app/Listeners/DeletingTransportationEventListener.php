<?php

namespace App\Listeners;

use App\Events\DeletingTransportationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingTransportationEventListener
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
     * @param  DeletingTransportationEvent  $event
     * @return void
     */
    public function handle(DeletingTransportationEvent $event)
    {
        $transaction    = $event->transportation->transaction()->firstOrFail();

        if($event->transportation->isForceDeleting()) {
            $transaction->forceDelete();
        } else {
            $transaction->delete();
        }
    }
}
