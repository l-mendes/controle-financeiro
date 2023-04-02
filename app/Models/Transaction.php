<?php

namespace App\Models;

use App\Enums\Type;
use App\Traits\MultiTenancyTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
    ];

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')->subCategory()->withTrashed();
    }

    public function scopeDone(Builder $builder): Builder
    {
        return $builder->where('done', true);
    }

    public function scopeBetweenDates(Builder $builder, Carbon $startDate, Carbon $endDate, string $timezone): Builder
    {
        return $builder->whereBetween(
            DB::raw("CONVERT_TZ(performed_at, 'UTC', '$timezone')"),
            [
                $startDate->toDateTimeString(),
                $endDate->toDateTimeString()
            ]
        );
    }

    public function scopeInbound(Builder $builder): Builder
    {
        return $builder->where('type', Type::INBOUND->value);
    }

    public function scopeOutbound(Builder $builder): Builder
    {
        return $builder->where('type', Type::OUTBOUND->value);
    }
}
