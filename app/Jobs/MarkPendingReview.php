<?php

namespace App\Jobs;

use App\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MarkPendingReview
{
    use Dispatchable, Queueable, SerializesModels;

    protected $model;
    protected $id;

    public function __construct(string $model, $id)
    {
        $this->model = $model;
        $this->id    = $id;
    }

    public function handle(): void
    {
        $model = $this->model;

        $instance = ($this->model && is_numeric($this->id) && $this->id !== 'lens')
                  ? $this->getInstance()
                  : null;

        if (!$instance || $instance->status !== $model::ST_NEW) {
            return;
        }

        $instance->status = $model::ST_PENDING_REVIEW;
        $instance->admin_first_viewed_at = now();
        $instance->save();
    }

    private function getInstance()
    {
        return rescue( function () {
            $m = $this->model;
            return $m::getCached($this->id);
        });
    }
}
