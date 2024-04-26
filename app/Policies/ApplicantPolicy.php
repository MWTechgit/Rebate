<?php

namespace App\Policies;

use App\Admin;
use App\Applicant;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicantPolicy
{
    use HandlesAuthorization;

    public function view(Admin $admin, Applicant $applicant)
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

    public function update(Admin $admin, Applicant $applicant)
    {
        return $admin->canWrite();
    }

    public function delete(Admin $admin, Applicant $applicant)
    {
        return false;
    }

    public function restore(Admin $admin, Applicant $applicant)
    {
        return $admin->canWrite();
    }

    public function forceDelete(Admin $admin, Applicant $applicant)
    {
        return false;
    }
}