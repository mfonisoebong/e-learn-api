<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OneTimePassword extends Model
{
    protected $fillable = [
        'code',
        'expires_at',
        'user_id',
        'type'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = random_int(100000, 999999);
            $model->expires_at = now()->addMinutes(15);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIsExpiredAttribute($value): bool
    {
        return $this->expires_at->isPast();
    }

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }
}
