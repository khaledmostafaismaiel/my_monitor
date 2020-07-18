<?php

namespace App\Mail;
use App\User ;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpenseAdded extends Mailable
{
    use Queueable, SerializesModels;

    public  $expense ;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($expense)
    {
        $this->expense = $expense ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.expense_added');
    }
}
