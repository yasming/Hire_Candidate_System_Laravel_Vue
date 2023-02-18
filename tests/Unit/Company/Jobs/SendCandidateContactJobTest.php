<?php

namespace Tests\Unit\Company\Jobs;

use App\Jobs\Candidate\SendCandidateContactJob;
use App\Mail\Candidate\ContactCandidateMail;
use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendCandidateContactJobTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
        $this->company = Company::first();
        $this->candidate = Candidate::first();
    }

    public function test_it_should_not_send_email_either_decrement_wallet_when_company_does_not_have_enough_coins()
    {
        Mail::fake();
        $this->company->wallet->update(['coins' => 2]);
        $job = new SendCandidateContactJob($this->candidate, $this->company);
        $job->handle();
        Mail::assertNotSent(ContactCandidateMail::class);
        $this->company->refresh();
        $this->assertEquals(2, $this->company->coins);
    }

    public function test_it_should_not_decrement_wallet_when_email_fails()
    {
        Mail::fake();
        Mail::shouldReceive('send')->andReturn('demo');
        $job = new SendCandidateContactJob($this->candidate, $this->company);
        $job->handle();
        $this->company->refresh();
        $this->assertEquals(20, $this->company->coins);
    }

}
