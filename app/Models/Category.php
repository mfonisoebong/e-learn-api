<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'featured_image',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $slug = str()->slug($model->title);
            $model->slug = $model->where('slug', $slug)->exists() ? $slug.'-'.(int) $model->max('id') + 1 : $slug;
        });
    }
}
