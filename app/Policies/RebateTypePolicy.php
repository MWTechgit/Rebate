<?php

namespace App\Policies;

use App\Admin;
use App\RebateType;
use Illuminate\Auth\Access\HandlesAuthorization;

class RebateTypePolicy
{
    use HandlesAuthorization;

    public function view(Admin $admin, RebateType $rebateType)
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

    public function update(Admin $admin, RebateType $rebateType)
    {
        return $admin->canWrite();
    }

    public function delete(Admin $admin, RebateType $rebateType)
    {
        return false;
    }

    public function restore(Admin $admin, RebateType $rebateType)
    {
        return false;
    }

    public function forceDelete(Admin $admin, RebateType $rebateType)
    {
        return false;
    }
}