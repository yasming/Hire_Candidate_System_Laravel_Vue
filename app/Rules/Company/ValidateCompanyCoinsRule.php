<?php

namespace App\Rules\Company;

use App\Models\Company;
use Illuminate\Contracts\Validation\Rule;

class ValidateCompanyCoinsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Company::find($value)->coins >= 5;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Company does not have enough coins for this action.';
    }
}
