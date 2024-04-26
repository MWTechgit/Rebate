<?php

namespace App\Events;

use App\Application;
use Illuminate\Queue\SerializesModels;

final class ApplicationWasAcceptedFromWaitList
{
    use SerializesModels;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }
}
