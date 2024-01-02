<?php

namespace App\Models;

use App\Enums\Type;
use App\Traits\MultiTenancyTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function performedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->setTimezone(auth()->user()?->timezone ?? config('app.timezone')),
            set: fn ($value) => Carbon::parse($value, auth()->user()?->timezone)->setTimezone(config('app.timezone'))
        );
    }

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
        $appTimezone = config('app.timezone');

        return $builder->whereBetween(
            DB::raw("CONVERT_TZ(performed_at, '$appTimezone', '$timezone')"),
            [
                $startDate->toDateTimeString(),
                $endDate->toDateTimeString()
            ]
        );
    }

    public function scopeInbound(Builder $builder): Builder
    {
        return $builder->where($builder->qualifyColumn('type'), Type::INBOUND);
    }

    public function scopeOutbound(Builder $builder): Builder
    {
        return $builder->where($builder->qualifyColumn('type'), Type::OUTBOUND);
    }
}
