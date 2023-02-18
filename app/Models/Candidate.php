<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    public function isHiredBySpecificCompany(int $companyId)
    {
        return CompanyCandidateHire::query()->where(['candidate_id' => $this->id, 'company_id' => $companyId])->exists();
    }
}
