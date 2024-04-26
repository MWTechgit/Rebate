<?php

namespace App\Policies;

use App\Admin;
use App\Rebate;
use Illuminate\Auth\Access\HandlesAuthorization;

class RebatePolicy
{
    use HandlesAuthorization;

    public function view(Admin $admin, Rebate $rebate)
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

    public function update(Admin $admin, Rebate $rebate)
    {
        return $admin->canWrite();
    }

    public function delete(Admin $admin, Rebate $rebate)
    {
        return $admin->canWrite();
    }

    public function restore(Admin $admin, Rebate $rebate)
    {
        return $admin->canWrite();
    }

    public function forceDelete(Admin $admin, Rebate $rebate)
    {
        return $admin->canWrite();
    }
}
