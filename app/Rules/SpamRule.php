<?php

namespace App\Rules;

use App\Inspections\Spam;
use Illuminate\Contracts\Validation\Rule;

class SpamRule implements Rule
{
    protected $errorMessage;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $inspection = app(Spam::class)->inspect($value);

        if (is_string($inspection)) {
            $this->errorMessage = $inspection;
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
