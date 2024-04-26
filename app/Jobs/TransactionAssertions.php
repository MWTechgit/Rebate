<?php

namespace App\Jobs;

use App\Exceptions\InvalidTransactionAttempt;

trait TransactionAssertions
{
    protected function assertApplicationApproved(): void
    {
        $this->assertApplicationHasTransaction();

        if (false === $this->claim()->application->isApproved()) {
            $this->throwApplicationNotApproved();
        }
    }

    protected function assertApplicationHasTransaction(): void
    {
        if (false === $this->claim()->application->hasTransaction()) {
            $this->throwNoTransaction();
        }
    }

    protected function assertNoClaimTransaction(): void
    {
        if ($this->claim()->hasTransaction()) {
            $message = "Attempting to {$this->action()} when claim already ";
            $message .= "has a transaction of type {$this->claim()->transaction->type}. ";
            $message .= "Claim ({$this->claim()->id}) Application ({$this->claim()->application->id})";
            throw new InvalidTransactionAttempt($message);
        }
    }

    protected function throwNoTransaction(): void
    {
        $message = "Attempting to {$this->action()} when application has no transaction. ";
        $message .= "Claim ({$this->claim()->id}) Application ({$this->claim()->application->id})";
        throw new InvalidTransactionAttempt($message);
    }

    protected function throwApplicationNotApproved(): void
    {
        $message = "You must approve the application before attempting to {$this->action()}";
        // $message .= "Claim ({$this->claim()->id}) Application ({$this->claim()->application->id})";
        throw new InvalidTransactionAttempt($message);
    }

    abstract public function claim(): Claim;

    abstract public function action(): string;
}