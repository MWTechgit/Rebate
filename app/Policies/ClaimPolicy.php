<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClaimPolicy
{
    use HandlesAuthorization;

    public function view(Admin $admin, \App\Claim $claim)
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

    public function update(Admin $admin, \App\Claim $claim)
    {
        return $admin->canWrite();
    }

    public function delete(Admin $admin, \App\Claim $claim)
    {
        return $admin->isSuperAdmin();
    }

    public function restore(Admin $admin, \App\Claim $claim)
    {
        return true;
    }

    public function forceDelete(Admin $admin, \App\Claim $claim)
    {
        return $admin->isSuperAdmin();
    }
}