<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Admin;
use App\Application;

class ApplicationPolicy
{
    use HandlesAuthorization;

    public function view(Authenticatable $user, Application $application)
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

    public function update(Authenticatable $user, Application $application)
    {
        return $user->canWrite();
    }

    public function delete(Authenticatable $user, Application $application)
    {
        return $user->isSuperAdmin();
    }

    public function restore(Authenticatable $user, Application $application)
    {
        return $user->canWrite();
    }

    public function forceDelete(Authenticatable $user, Application $application)
    {
        return $user->isSuperAdmin();
    }
}