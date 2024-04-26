<?php

namespace App\Events;

use App\Application;
use Illuminate\Queue\SerializesModels;

final class ApplicationWasCreated
{
    use SerializesModels;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }
}
