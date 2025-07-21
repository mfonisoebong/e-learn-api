<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'featured_image',
        'user_id',
        'description',
        'difficulty_level',
        'category_id',
        'requirements',
        'learning_objectives',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $builder)
    {
        $builder->when(request('search'), function (Builder $query) {
            $query->where('title', 'like', '%'.request('search').'%')
                ->orWhere('description', 'like', '%'.request('search').'%')
                ->orWhere('slug', 'like', '%'.request('search').'%')
                ->orWhereHas('category', function ($query) {
                    $query->where('title', 'like', '%'.request('search').'%');
                });
        });

        $builder->when(request('category_id'), function (Builder $query) {
            $query->where('category_id', request('category_id'));
        });
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    protected function casts(): array
    {
        return [
            'requirements' => 'array',
            'learning_objectives' => 'array',
        ];
    }
}
