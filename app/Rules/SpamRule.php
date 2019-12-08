<?php

namespace App\Rules;

use App\Inspections\Spam;
use Illuminate\Contracts\Validation\Rule;

class SpamRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return app(Spam::class)->inspect($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute contains spam.';
    }
}
