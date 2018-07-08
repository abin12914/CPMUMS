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
        $transaction = $event->purchase->transaction()->firstOrFail();

        if($event->purchase->isForceDeleting()) {
            $transaction->forceDelete();
        } else {
            $transaction->delete();
        }
    }
}
