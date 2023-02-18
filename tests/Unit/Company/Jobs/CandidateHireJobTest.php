<?php

namespace Tests\Unit\Company\Jobs;

use App\Jobs\Candidate\CandidateHireJob;
use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CandidateHireJobTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
        $this->company = Company::first();
        $this->candidate = Candidate::first();
    }

    public function test_it_should_not_increment_wallet_when_email_fails()
    {
        Mail::fake();
        Mail::shouldReceive('send')->andReturn('test');
        $job = new CandidateHireJob($this->candidate, $this->company);
        $job->handle();
        $this->company->refresh();
        $this->assertEquals(20, $this->company->coins);
    }

}
