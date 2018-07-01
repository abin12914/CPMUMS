<?php

namespace App\Listeners;

use App\Events\DeletingPurchaseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingPurchaseEventListener
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
     * @param  DeletingPurchaseEvent  $event
     * @return void
     */
    public function handle(DeletingPurchaseEvent $event)
    {
        if($event->purchase->isForceDeleting()) {
            $event->purchase->transaction()->forceDelete();
        } else {
            $event->purchase->transaction()->delete();
        }
    }
}
