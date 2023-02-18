<?php

namespace App\Rules\Company;

use App\Models\Candidate;
use Illuminate\Contracts\Validation\Rule;

class ValidateCompanyAlreadyContactCandidateBeforeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private ?string $candidate)
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
        if ($this->candidate == null) {
            return false;
        }
        return Candidate::find($this->candidate)->companiesCandidateContacts()->where('company_id', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'To hire this candidate you need to contact him before.';
    }
}
