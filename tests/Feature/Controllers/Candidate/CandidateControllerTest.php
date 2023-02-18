<?php

namespace Tests\Feature\Controllers\Candidate;

use App\Mail\Candidate\ContactCandidateMail;
use App\Mail\Candidate\CandidateHireMail;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\CompanyCandidateContact;
use App\Models\CompanyCandidateHire;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CandidateControllerTest extends TestCase
{
    private Company $company;
    private Candidate $candidate;
    private int $companyCoins;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
        $this->company = Company::first();
        $this->candidate = Candidate::first();
        $this->companyCoins = $this->company->coins;
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

    public function test_should_validate_company_has_enough_coins_on_candidates_contact_endpoint()
    {
        $this->company->wallet->update(['coins' => 2]);
        $this->post('/candidates-contact', ['companyId' => $this->company->id])->assertStatus(302)
            ->assertSessionHasErrors(['companyId' => 'Company does not have enough coins for this action.']);
    }

    public function test_should_successfully_send_a_contact_email_to_candidate_and_decrement_company_wallet_on_candidates_contact_endpoint()
    {
        Mail::fake();
        $response = $this->post('/candidates-contact', ['companyId' => $this->company->id, 'candidateId' => $this->candidate->id])
            ->assertStatus(200);

        Mail::assertSent(ContactCandidateMail::class, function ($mail) {
            $mail->build();
            $this->assertEquals($this->company->name, $mail->companyName);
            $this->assertEquals($this->candidate->name, $mail->candidateName);
            $this->assertTrue($mail->hasTo($this->candidate->email));
            return $mail->subject === 'One company wants to hire you !!';
        });
        $this->company->refresh();
        $this->assertEquals($this->companyCoins - 5, $this->company->coins);
        $this->assertEquals(1, CompanyCandidateContact::count());
        $this->assertEquals(['message' => 'Email contact will be send to the candidate'], $response->json());
    }

    // -----------

    public function test_should_validate_candidate_id_required_on_candidates_hire_endpoint()
    {
        $this->post('/candidates-hire', [])->assertStatus(302)
            ->assertSessionHasErrors(['candidateId' => 'The candidate id field is required.']);
    }

    public function test_should_validate_candidate_id_exists_on_candidates_hire_endpoint()
    {
        $this->post('/candidates-hire', ['candidateId' => 0])->assertStatus(302)
            ->assertSessionHasErrors(['candidateId' => 'The selected candidate id is invalid.']);
    }


    public function test_should_validate_company_id_required_on_candidates_hire_endpoint()
    {
        $this->post('/candidates-hire', [])->assertStatus(302)
            ->assertSessionHasErrors(['companyId' => 'The company id field is required.']);
    }

    public function test_should_validate_company_id_exists_on_candidates_hire_endpoint()
    {
        $this->post('/candidates-hire', ['companyId' => 0])->assertStatus(302)
            ->assertSessionHasErrors(['companyId' => 'The selected company id is invalid.']);
    }

    public function test_should_validate_company_contact_with_candidate_before_hire_on_candidates_hire_endpoint()
    {
        $this->post('/candidates-hire', ['companyId' => $this->company->id, 'candidateId' => $this->candidate->id])->assertStatus(302)
            ->assertSessionHasErrors(['companyId' => 'To hire this candidate you need to contact him before.']);
    }

    public function test_should_successfully_send_a_hire_email_to_candidate_increment_company_wallet_and_mark_candidate_as_hired_on_candidates_hire_endpoint()
    {
        Mail::fake();
        CompanyCandidateContact::query()->create(['company_id' => $this->company->id, 'candidate_id' => $this->candidate->id]);
        $response = $this->post('/candidates-hire', ['companyId' => $this->company->id, 'candidateId' => $this->candidate->id])
            ->assertStatus(200);

        Mail::assertSent(CandidateHireMail::class, function ($mail) {
            $mail->build();
            $this->assertEquals($this->company->name, $mail->companyName);
            $this->assertEquals($this->candidate->name, $mail->candidateName);
            $this->assertTrue($mail->hasTo($this->candidate->email));
            return $mail->subject === 'You are hired !!';
        });

        $this->company->refresh();
        $this->assertEquals($this->companyCoins + 5, $this->company->coins);
        $this->assertEquals(1, CompanyCandidateHire::count());
        $this->assertequals(true, $this->candidate->isHiredBySpecificCompany($this->company->id));
        $this->assertEquals(['message' => 'Hire email will be send to the candidate'], $response->json());
    }
}
