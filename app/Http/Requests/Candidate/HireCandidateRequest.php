<?php

namespace App\Http\Requests\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class HireCandidateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'candidateId' => 'bail|required|exists:candidates,id',
            'companyId' => 'bail|required|exists:companies,id'
        ];
    }
}
