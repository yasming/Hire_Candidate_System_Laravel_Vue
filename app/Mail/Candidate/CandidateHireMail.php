<?php

namespace App\Mail\Candidate;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateHireMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public string $candidateName, public string $companyName)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('test@mails.app.test.com', 'test')
            ->subject('You are hired !!')
            ->view('mails.candidates.hire_candidate');
    }
}
