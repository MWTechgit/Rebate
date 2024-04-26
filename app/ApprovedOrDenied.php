<?php

namespace App;

trait ApprovedOrDenied
{
    abstract public function transaction();

    abstract public function isExpired(): bool;

    public function hasClaimedRebate(): bool 
    {
        return !$this->isExpired() && !$this->isDenied();
    }


    public function hasTransaction(): bool
    {
        return false === empty($this->transaction);
    }

    public function deleteTransaction(): void
    {
        if ($this->hasTransaction()) {
            $this->transaction->delete();
        }
    }

    public function isApproved(): bool
    {
        if (empty($this->transaction)) {
            return false;
        }

        return $this->transaction->isApproved();
    }

    public function isDenied(): bool
    {
        if (empty($this->transaction)) {
            return false;
        }

        return $this->transaction->isDenied();
    }

    public function denial(): Denial
    {
        if (false === $this->isDenied()) {
            return new Denial;
        }

        return new Denial($this->transaction->description);
    }

    public function transactionBy()
    {
        if (false === $this->hasTransaction()) {
            return null;
        }

        return $this->transaction->admin;
    }
}