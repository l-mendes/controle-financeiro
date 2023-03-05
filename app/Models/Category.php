<?php

namespace App\Models;

use App\Enums\CategoryType;
use App\Traits\MultiTenancyTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, MultiTenancyTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['user_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => CategoryType::class,
    ];

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'category_id', 'id');
    }

    public function scopeMainCategory(Builder $query): Builder
    {
        return $query->whereNull('category_id');
    }
}
