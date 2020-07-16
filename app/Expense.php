<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //for protecting from mass assaiment // some hacker try to add input field like id in html
    protected $fillable = [
        'user_id' ,
        'expense_name' ,
        'price' ,
        'category' ,
        'comment' ,
        'created_at'
    ] ;

//    protected $guarded = [] ;

    public function user(){//return expenses which belongs to that user
        return $this->belongsTo(User::class);
    }
}
