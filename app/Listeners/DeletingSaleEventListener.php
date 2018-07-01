<?php

namespace App\Listeners;

use App\Events\DeletingSaleEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingSaleEventListener
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
     * @param  DeletingSaleEvent  $event
     * @return void
     */
    public function handle(DeletingSaleEvent $event)
    {
        if($event->sale->isForceDeleting()) {
            $event->sale->transaction()->forceDelete();
        } else {
            $event->sale->transaction()->delete();
        }
    }
}
