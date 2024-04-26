<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartnerPolicy
{
    use HandlesAuthorization;

    public function view(Admin $admin, \App\Partner $partner)
    {
        return true;
    }

    public function viewAny(Admin $admin)
    {
        return true;
    }

    public function create(Admin $admin)
    {
        return $admin->canWrite();
    }

    public function update(Admin $admin, \App\Partner $partner)
    {
        return $admin->canWrite();
    }

    public function delete(Admin $admin, \App\Partner $partner)
    {
        return $admin->canWrite();
    }

    public function restore(Admin $admin, \App\Partner $partner)
    {
        return $admin->canWrite();
    }

    public function forceDelete(Admin $admin, \App\Partner $partner)
    {
        return $admin->canWrite();
    }
}
