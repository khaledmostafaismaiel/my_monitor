<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relationships
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function monthYear()
    {
        return $this->belongsTo(MonthYear::class);
    }

    // Self-referential relationships for nesting
    public function parent()
    {
        return $this->belongsTo(Todo::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Todo::class, 'parent_id')->orderBy('order');
    }

    // Recursive eager loading for unlimited nesting
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    // Get all descendants (flat collection)
    public function descendants()
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->descendants());
        }

        return $descendants;
    }

    // Scopes
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeVisible($query, $userId, $familyId)
    {
        return $query->where('family_id', $familyId)
            ->where(function ($q) use ($userId) {
                $q->where('scope', 'public')
                    ->orWhere('user_id', $userId);
            });
    }

    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByPriority($query, $priority)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        return $query;
    }

    public function scopeByMonthYear($query, $monthYearId)
    {
        if ($monthYearId) {
            return $query->where('month_year_id', $monthYearId);
        }
        return $query;
    }

    // Helper methods
    public function isOverdue()
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }

    public function getProgressAttribute()
    {
        $children = $this->children;

        if ($children->isEmpty()) {
            return $this->status === 'completed' ? 100 : 0;
        }

        $completed = $children->where('status', 'completed')->count();
        return round(($completed / $children->count()) * 100);
    }

    public function getChildrenCountAttribute()
    {
        return $this->descendants()->count();
    }

    // Cascade delete children
    protected static function booted()
    {
        static::deleting(function ($todo) {
            $todo->children()->each(function ($child) {
                $child->delete();
            });
        });
    }
}
