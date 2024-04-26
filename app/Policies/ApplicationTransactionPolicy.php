<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\ApplicationTransaction;

class ApplicationTransactionPolicy
{
    use HandlesAuthorization;

    public function view(Authenticatable $user, ApplicationTransaction $transaction)
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

    public function update(Authenticatable $user, ApplicationTransaction $transaction)
    {
        return false;
    }

    public function delete(Authenticatable $user, ApplicationTransaction $transaction)
    {
        return false;
    }

    public function restore(Authenticatable $user, ApplicationTransaction $transaction)
    {
        return false;
    }

    public function forceDelete(Authenticatable $user, ApplicationTransaction $transaction)
    {
        return false;
    }
}