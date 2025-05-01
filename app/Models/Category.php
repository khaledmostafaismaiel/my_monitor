<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [] ;

    public function blueprintTransactions()
    {
        return $this->hasMAny(BlueprintTransaction::class);
    }

    public function normalTransactions()
    {
        return $this->hasMAny(NormalTransaction::class);
    }

}
