<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCandidateContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'candidate_id'
    ];
}
