<?php

namespace App\Listeners;

use App\Events\DeletingTransactionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingTransactionEventListener
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
     * @param  DeletingTransactionEvent  $event
     * @return void
     */
    public function handle(DeletingTransactionEvent $event)
    {
        if($event->transaction->isForceDeleting()) {
            $event->transaction->account()->forceDelete();
        } else {
            $event->transaction->account()->delete();
        }
    }
}
