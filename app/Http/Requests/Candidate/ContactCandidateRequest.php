<?php

namespace App\Http\Requests\Candidate;

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
            'candidateId' => 'required|exists:candidates,id',
            'companyId' => 'required|exists:companies,id'
        ];
    }
}
