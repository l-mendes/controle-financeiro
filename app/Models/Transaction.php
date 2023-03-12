<?php

namespace App\Models;

use App\Casts\Money;
use App\Enums\Type;
use App\Traits\MultiTenancyTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory, MultiTenancyTrait;

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
        'performed_at' => 'datetime',
        'amount' => Money::class
    ];

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')->subCategory()->withTrashed();
    }

    public function scopeDone(Builder $builder): Builder
    {
        return $builder->where('done', true);
    }
}
