<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::stringable(Carbon::class, function ($object) {
            return $object->timezone(auth()->user()?->timezone ?? config('app.timezone'))->format('d/m/Y H:i');
        });

        Blade::directive('datetime', function (string $expression) {
            $timezone = auth()->user()?->timezone ?? config('app.timezone');

            return "<?php echo \Illuminate\Support\Carbon::parse(($expression))->timezone($timezone)->format('d/m/Y H:i'); ?>";
        });

        Blade::directive('money', function (string $expression) {
            return "<?php echo \Brick\Money\Money::ofMinor(($expression), 'BRL')->formatTo(config('app.locale')) ?>";
        });
    }
}
