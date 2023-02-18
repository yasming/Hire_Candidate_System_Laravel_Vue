<?php

namespace App\Jobs\Candidate;

use App\Mail\Candidate\ContactCandidateMail;
use App\Mail\Candidate\CandidateHireMail;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\CompanyCandidateHire;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CandidateHireJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Candidate $candidate, private Company $company)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->candidate->email)->send(new CandidateHireMail(candidateName: $this->candidate->name, companyName: $this->company->name));
            CompanyCandidateHire::query()->create(['company_id' => $this->company->id, 'candidate_id' => $this->candidate->id]);
            $this->company->wallet->increment('coins', 5);
        } catch (\Exception $e) {
            Log::info('Send hire job failed: '. $e->getMessage());
        }
    }
}
