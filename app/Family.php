<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $guarded = [] ;

    protected $table = 'families';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

}
