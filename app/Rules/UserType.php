<?php

namespace App\Rules;

use App\Enums\UserType as EnumsUserType;
use App\Models\User;
use App\Models\UserType as ModelsUserType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserType implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        
        EnumsUserType::tryFrom($value) != null ? null : $fail(trans('validation.user_type_not_registered'));
    }
}
