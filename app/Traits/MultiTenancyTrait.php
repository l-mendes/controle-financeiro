<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait MultiTenancyTrait
{
    public static function bootMultiTenancyTrait()
    {
        if (!app()->runningInConsole() && auth()->check()) {
            static::creating(function ($model) {
                $model->user_id = auth()->user()->id;
            });

            static::addGlobalScope('created_by_user_id', function (Builder $builder) {
                $builder->where($builder->qualifyColumn('user_id'), auth()->user()->id);
            });
        }
    }
}
