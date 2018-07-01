<?php

namespace App\Listeners;

use App\Events\DeletingExpenseEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletingExpenseEventListener
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
     * @param  DeletingExpenseEvent  $event
     * @return void
     */
    public function handle(DeletingExpenseEvent $event)
    {
        if($event->expense->isForceDeleting()) {
            $event->expense->transaction()->forceDelete();
        } else {
            $event->expense->transaction()->delete();
        }
    }
}
