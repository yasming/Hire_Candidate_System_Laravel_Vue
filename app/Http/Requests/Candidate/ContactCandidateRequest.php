<?php

namespace App\Http\Requests\Candidate;

use App\Rules\Company\ValidateCompanyCoinsRule;
use Illuminate\Foundation\Http\FormRequest;

class ContactCandidateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'candidateId' => 'bail|required|exists:candidates,id',
            'companyId' => ['bail','required','exists:companies,id', new ValidateCompanyCoinsRule()]
        ];
    }
}
