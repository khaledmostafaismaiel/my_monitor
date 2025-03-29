<?php

namespace App;

use App\Mail\ExpenseAdded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Expense extends Model
{
    protected $guarded = [] ;


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

}
