<?php

namespace App\Models;

use App\Enums\Type;
use App\Traits\MultiTenancyTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'type' => Type::class,
    ];

    public function subCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'category_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeMainCategory(Builder $query): Builder
    {
        return $query->whereNull('category_id');
    }

    public function scopeSubCategory(Builder $query): Builder
    {
        return $query->whereNotNull('category_id');
    }

    public function scopeOfCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }
}
