<?php

namespace App\Actions\Candidates;

use App\Mail\Candidate\ContactCandidateMail;
use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;

class ContactCandidateAction
{

    public static function run(string $candidateId, string $companyId): void
    {
        $candidate = Candidate::query()->find($candidateId);
        $company = Company::query()->find($companyId);
        Mail::to($candidate->email)->queue(new ContactCandidateMail(candidateName: $candidate->name, companyName: $company->name));
        $company->wallet->decrement('coins', 5);
    }
}
