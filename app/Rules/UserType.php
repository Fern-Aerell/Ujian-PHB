<?php

namespace App\Rules;

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
        ModelsUserType::where('type', $value)->first() ? null : $fail(trans('validation.user_type_not_registered'));
    }
}
