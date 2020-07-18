<?php

namespace App\Listeners;

use App\Events\ExpenseAdded;
use App\Mail\ExpenseAdded as ExpenseAddedMail;
use App\User ;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendExpenseAddedMail
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
     * @param  ExpenseAdded  $event
     * @return void
     */
    public function handle(ExpenseAdded $event)
    {
            Mail::to(User::first()->user_name)->send(
                new ExpenseAddedMail($event->expense)
            );
    }
}
