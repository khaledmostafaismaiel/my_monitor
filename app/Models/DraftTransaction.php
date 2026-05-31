<?php

namespace App\Models;

use Parental\HasParent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DraftTransaction extends Transaction
{
    use HasFactory;
    use HasParent;

}
