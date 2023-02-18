<?php

namespace App\Jobs\Candidate;

use App\Mail\Candidate\ContactCandidateMail;
use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCandidateContactJob implements ShouldQueue
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
            if ($this->company->coins < 5) {
                throw new \Exception('Company does not have enough coins');
            }
            Mail::to($this->candidate->email)->send(new ContactCandidateMail(candidateName: $this->candidate->name, companyName: $this->company->name));
            $this->company->wallet->decrement('coins', 5);
        } catch (\Exception $e) {
            Log::info('Send email job failed: '. $e->getMessage());
        }
    }
}
