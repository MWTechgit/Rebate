<?php

namespace App\Policies;

use App\Admin;
use App\UtilityAccount;
use Illuminate\Auth\Access\HandlesAuthorization;

class UtilityAccountPolicy
{
    use HandlesAuthorization;

    public function view(Admin $admin, UtilityAccount $utilityAccount): bool
    {
        return true;
    }

    public function viewAny(Admin $admin)
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return $admin->canWrite();
    }

    public function update(Admin $admin, UtilityAccount $utilityAccount): bool
    {
        return $admin->canWrite();
    }

    public function delete(Admin $admin, UtilityAccount $utilityAccount): bool
    {
        return $admin->canWrite();
    }

    public function restore(Admin $admin, UtilityAccount $utilityAccount): bool
    {
        return $admin->canWrite();
    }

    public function forceDelete(Admin $admin, UtilityAccount $utilityAccount): bool
    {
        return $admin->canWrite();
    }
}
