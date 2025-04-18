<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Family extends Model
{
    use HasFactory;

    protected $guarded = [] ;

    protected $table = 'families';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($family) {
            if (!$family->id) {
                $family->id = (string) Str::uuid();
            }
        });
    }

    public function monthYears()
    {
        return $this->hasMany(MonthYear::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function normalTransactions()
    {
        return $this->hasMany(NormalTransaction::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function blueprintTransactions()
    {
        return $this->hasMany(BlueprintTransaction::class);
    }

}
