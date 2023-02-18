<?php

namespace Tests\Feature\Controllers\Candidate;

use App\Mail\Candidate\ContactCandidateMail;
use App\Mail\Login\LoginEmail;
use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CandidateControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_should_validate_candidate_id_required_on_candidates_contact_endpoint()
    {
        $this->post('/candidates-contact', [])->assertStatus(302)
            ->assertSessionHasErrors(['candidateId' => 'The candidate id field is required.']);
    }

    public function test_should_validate_candidate_id_exists_on_candidates_contact_endpoint()
    {
        $this->post('/candidates-contact', ['candidateId' => 0])->assertStatus(302)
            ->assertSessionHasErrors(['candidateId' => 'The selected candidate id is invalid.']);
    }


    public function test_should_validate_company_id_required_on_candidates_contact_endpoint()
    {
        $this->post('/candidates-contact', [])->assertStatus(302)
            ->assertSessionHasErrors(['companyId' => 'The company id field is required.']);
    }

    public function test_should_validate_company_id_exists_on_candidates_contact_endpoint()
    {
        $this->post('/candidates-contact', ['companyId' => 0])->assertStatus(302)
            ->assertSessionHasErrors(['companyId' => 'The selected company id is invalid.']);
    }

    public function test_should_successfully_send_a_contact_email_to_candidate_on_candidates_contact_endpoint()
    {
        Mail::fake();
        $company = Company::first();
        $candidate = Candidate::first();
        $companyCoins = $company->coins;
        $response = $this->post('/candidates-contact', ['companyId' => $company->id, 'candidateId' => $candidate->id])
            ->assertStatus(200);

        Mail::assertQueued(ContactCandidateMail::class, function ($mail) use($company, $candidate){
            $mail->build();
            $this->assertEquals($company->name, $mail->companyName);
            $this->assertEquals($candidate->name, $mail->candidateName);
            $this->assertTrue($mail->hasTo($candidate->email));
            return $mail->subject === 'One company wants to hire you !!';
        });
        $company->refresh();
        $this->assertEquals($companyCoins - 5, $company->coins);
        $this->assertEquals(['message' => 'Email contact sent to the candidate'], $response->json());
    }
}
