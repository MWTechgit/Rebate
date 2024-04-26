<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentSetPolicy
{
    use HandlesAuthorization;

    public function view(Admin $admin, \App\DocumentSet $documentSet)
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

    public function update(Admin $admin, \App\DocumentSet $documentSet)
    {
        return $admin->canWrite();
    }

    public function delete(Admin $admin, \App\DocumentSet $documentSet)
    {
        return $admin->canWrite();
    }

    public function restore(Admin $admin, \App\DocumentSet $documentSet)
    {
        return $admin->canWrite();
    }

    public function forceDelete(Admin $admin, \App\DocumentSet $documentSet)
    {
        return $admin->canWrite();
    }
}
