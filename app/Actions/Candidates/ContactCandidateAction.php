<?php

namespace App\Actions\Candidates;

use App\Jobs\Candidate\CandidateContactJob;
use App\Models\Candidate;
use App\Models\Company;

class ContactCandidateAction
{

    public static function run(string $candidateId, string $companyId): void
    {
        $candidate = Candidate::query()->find($candidateId);
        $company = Company::query()->find($companyId);
        CandidateContactJob::dispatch($candidate, $company);
    }
}
