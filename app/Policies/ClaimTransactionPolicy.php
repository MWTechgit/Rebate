<?php

namespace App\Policies;

use App\Applicant;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ClaimTransactionPolicy
{
    use HandlesAuthorization;

    public function view(Authenticatable $user, \App\ClaimTransaction $claimTransaction)
    {
        return true;
    }

    public function viewAny(Authenticatable $user)
    {
        return true;
    }

    public function create(Authenticatable $user)
    {
        return false;
    }

    public function update(Authenticatable $user, \App\ClaimTransaction $claimTransaction)
    {
        return false;
    }

    public function delete(Authenticatable $user, \App\ClaimTransaction $claimTransaction)
    {
        return false;
    }

    public function restore(Authenticatable $user, \App\ClaimTransaction $claimTransaction)
    {
        return false;
    }

    public function forceDelete(Authenticatable $user, \App\ClaimTransaction $claimTransaction)
    {
        return false;
    }
}
