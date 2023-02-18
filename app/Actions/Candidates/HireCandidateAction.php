<?php

namespace App\Actions\Candidates;

use App\Jobs\Candidate\CandidateHireJob;
use App\Models\Candidate;
use App\Models\Company;

class HireCandidateAction
{

    public static function run(string $candidateId, string $companyId): void
    {
        $candidate = Candidate::query()->find($candidateId);
        $company = Company::query()->find($companyId);
        CandidateHireJob::dispatch($candidate, $company);
    }
}
