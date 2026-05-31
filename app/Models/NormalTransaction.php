<?php

namespace App\Models;

use Parental\HasParent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NormalTransaction extends Transaction
{
    use HasFactory;
    use HasParent;

}
