<?php

namespace App\Observers;

use App\Applicant;

class ApplicantObserver
{
    public function creating(Applicant $model)
    {
        $model->full_name = $model->first_name.' '.$model->last_name;
    }

    public function updating(Applicant $model)
    {
        $model->full_name = $model->first_name.' '.$model->last_name;
    }
}
