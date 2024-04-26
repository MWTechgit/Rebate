<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    use DispatchesJobs;

    protected $signature = 'test:email';

    protected $description = 'Send Erin a test email';

    public function handle()
    {

        Mail::send([], [], function (Message $message) {
            $message->to('erinlambro@gmail.com')
            ->subject('From Rebates: Mail Works')
            ->setBody('<b>My email</b>', 'text/html');
        });

    }
}
