<?php

namespace App\Providers;

use App\Enums\Type;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('types', Type::cases());

        Carbon::setLocale(config('app.locale'));

        FilamentIcon::register([
            'actions::delete-action' => 'heroicon-s-x-mark',
        ]);
    }
}
