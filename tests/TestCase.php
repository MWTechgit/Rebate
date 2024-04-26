<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function dispatch($job)
    {
        return $job->handle();
    }

    protected function runSeed($file)
    {
        $this->artisan('db:seed', ['--class' => $file, '--env' => 'testing']);
    }

    protected function getJsonData($file)
    {
        return json_decode(file_get_contents(__DIR__.'/data/'.$file.'.json'), true);
    }
}
