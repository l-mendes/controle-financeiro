<?php

if (!function_exists('currencyFormat')) {
    function currencyFormat(int|float|string $amount)
    {
        return \Brick\Money\Money::ofMinor($amount, 'BRL')->formatTo(config('app.locale'));
    }
}
