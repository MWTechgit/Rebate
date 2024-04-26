<?php

namespace App\Policies;

use App\Owner;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;

class OwnerPolicy
{
    use HandlesAuthorization;

    public function view(Authenticatable $user, Owner $owner)
    {
        return true;
    }

    public function viewAny(Authenticatable $admin)
    {
        return true;
    }

    public function create(Authenticatable $user)
    {
        return false;
    }

    public function update(Authenticatable $user, Owner $owner)
    {
        return $user->canWrite();
    }

    public function delete(Authenticatable $user, Owner $owner)
    {
        return $user->canWrite();
    }

    public function restore(Authenticatable $user, Owner $owner)
    {
        return $user->canWrite();
    }

    public function forceDelete(Authenticatable $user, Owner $owner)
    {
        return $user->canWrite();
    }
}