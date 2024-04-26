<?php

namespace App\Policies;

use App\Admin;
use App\Property;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    public function view(Admin $admin, Property $property)
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

    public function update(Admin $admin, Property $property)
    {
        return $admin->canWrite();
    }

    public function delete(Admin $admin, Property $property)
    {
        return false;
    }

    public function restore(Admin $admin, Property $property)
    {
        return $admin->canWrite();
    }

    public function forceDelete(Admin $admin, Property $property)
    {
        return false;
    }
}