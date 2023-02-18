<?php

namespace App\Http\Controllers;

use App\Actions\Candidates\ContactCandidateAction;
use App\Actions\Candidates\HireCandidateAction;
use App\Http\Requests\Candidate\ContactCandidateRequest;
use App\Http\Requests\Candidate\HireCandidateRequest;
use App\Models\Candidate;
use App\Models\Company;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CandidateController extends Controller
{
    public function index()
    {
        $company = Company::find(1);
        $coins = $company->coins;
        $companyId = $company->id;
        $candidates = Candidate::getNotHiredCandidatesForSpecificCompany($companyId);
        return view('candidates.index', compact('candidates', 'coins', 'companyId'));
    }

    /**
     * @throws Exception
     */
    public function contact(ContactCandidateRequest $request){
        try {
            ContactCandidateAction::run($request->validated('candidateId'), $request->validated('companyId'));
            return response()->json(['message' => 'Email contact will be send to the candidate']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Contact could not be sent to the candidate'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function hire(HireCandidateRequest $request)
    {
        try {
            HireCandidateAction::run($request->validated('candidateId'), $request->validated('companyId'));
            return response()->json(['message' => 'Hire email will be send to the candidate']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Contact could not be sent to the candidate'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
