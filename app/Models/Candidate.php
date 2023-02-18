<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    use HasFactory;

    public function isHiredBySpecificCompany(int $companyId): bool
    {
        return CompanyCandidateHire::query()->where(['candidate_id' => $this->id, 'company_id' => $companyId])->exists();
    }

    public function companiesCandidateHires(): HasMany
    {
        return $this->hasMany(CompanyCandidateHire::class);
    }

    public static function getNotHiredCandidatesForSpecificCompany(int $companyId): Collection
    {
        return self::query()->whereDoesntHave('companiesCandidateHires', function ($query) use ($companyId) {
            return $query->where('company_id', $companyId);
        })->get();
    }
}
