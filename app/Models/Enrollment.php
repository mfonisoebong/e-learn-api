<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'progress',
        'user_id',
        'course_id',
        'completed_lessons',
        'points'
    ];

    public function scopeFilter(Builder $builder)
    {
        $status = request('status');
        $builder->when($status, function (Builder $query) use ($status) {
            if ($status === 'completed') {
                $query->where('progress', '>=', '100');
            }
            if ($status === 'pending') {
                $query->where('progress', '<', '100');
            }
        });
        $builder->when(request('search'), function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('full_name', 'like', '%' . request('search') . '%');
            });
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getIsCompletedAttribute(): bool
    {
        return (int)$this->progress >= 100;
    }
}
