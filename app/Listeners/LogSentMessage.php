<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class LogSentMessage
{
    public function handle(MessageSent $event): void
    {
        Log::info('------a---');
        Log::info(json_encode($event));
    }
}
