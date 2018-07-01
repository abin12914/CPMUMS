<?php

namespace App\Listeners;

use App\Events\DeletingVoucherEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingVoucherEventListener
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
     * @param  DeletingVoucherEvent  $event
     * @return void
     */
    public function handle(DeletingVoucherEvent $event)
    {
        if($event->voucher->isForceDeleting()) {
            $event->voucher->transaction()->forceDelete();
        } else {
            $event->voucher->transaction()->delete();
        }
    }
}
