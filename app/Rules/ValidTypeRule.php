<?php

namespace App\Rules;

use App\Enums\Type;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTypeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array(strtoupper($value), array_column(Type::cases(), 'value'))) {
            $fail('O tipo é inválido.');
        }
    }
}
