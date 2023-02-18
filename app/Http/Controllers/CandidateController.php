<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(){
        $candidates = Candidate::all();
        $coins = Company::find(1)->coins;
        return view('candidates.index', compact('candidates', 'coins'));
    }

    // make validation request
    public function contact(Request $request){
        $candidateId = $request->only('candidateId');

    }

    public function hire(){
        // @todo
        // Your code goes here...
    }
}
