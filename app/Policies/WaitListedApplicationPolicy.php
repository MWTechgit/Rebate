<?php

namespace App\Policies;

use App\Admin;
use App\WaitListedApplication;
use Illuminate\Auth\Access\HandlesAuthorization;

class WaitListedApplicationPolicy
{
    use HandlesAuthorization;

    public function view(Admin $admin, WaitListedApplication $waitListedApplication)
    {
        return true;
    }

    public function viewAny(Admin $admin)
    {
        return true;
    }

    public function create(Admin $admin)
    {
        return false;
    }

    public function update(Admin $admin, WaitListedApplication $waitListedApplication)
    {
        return $admin->canWrite();
    }

    public function delete(Admin $admin, WaitListedApplication $waitListedApplication)
    {
        return $admin->canWrite();
    }

    public function restore(Admin $admin, WaitListedApplication $waitListedApplication)
    {
        return $admin->canWrite();
    }

    public function forceDelete(Admin $admin, WaitListedApplication $waitListedApplication)
    {
        return $admin->canWrite();
    }
}
