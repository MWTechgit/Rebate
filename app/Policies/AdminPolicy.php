<?php

namespace App\Policies;

use App\Admin;
use App\Admin as AuthAdmin;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policies defined here automatically determine
 * what users can do in the nova application.
 */
class AdminPolicy
{
    use HandlesAuthorization;

    public function view(AuthAdmin $authAdmin, Admin $admin): bool
    {
        return true;
    }

    public function viewAny(AuthAdmin $authAdmin): bool
    {
        return true;
    }

    public function create(AuthAdmin $authAdmin): bool
    {
        return $authAdmin->canWrite();
    }

    public function update(AuthAdmin $authAdmin, Admin $admin): bool
    {
        return $authAdmin->canWrite();
    }

    public function delete(AuthAdmin $authAdmin, Admin $admin): bool
    {
        return $authAdmin->canWrite();
    }

    public function restore(AuthAdmin $authAdmin, Admin $admin): bool
    {
        return $authAdmin->canWrite();
    }

    public function forceDelete(AuthAdmin $authAdmin, Admin $admin): bool
    {
        return false;
    }
}
