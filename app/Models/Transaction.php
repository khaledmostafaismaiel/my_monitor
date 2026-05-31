<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Parental\HasChildren;

class Transaction extends Model
{

    use HasChildren;
    use HasFactory;

    protected $table = 'transactions';

    protected $guarded = [];

    private const NORMAL_TRANSACTION = 'normal';
    private const BLUE_PRINT_TRANSACTION = 'blue_print';
    private const DRAFT_TRANSACTION = 'draft';

    protected $childTypes = [
        self::NORMAL_TRANSACTION => NormalTransaction::class,
        self::BLUE_PRINT_TRANSACTION => BlueprintTransaction::class,
        self::DRAFT_TRANSACTION => DraftTransaction::class,
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function monthYear(){
        return $this->belongsTo(MonthYear::class);
    }

    public function wallet(){
        return $this->belongsTo(Wallet::class);
    }
}
