<?php

namespace App\Observers;

use App\Admin;

class AdminObserver
{
    public function creating(Admin $model)
    {
        $this->setFullName($model);
    }

    public function updating(Admin $model)
    {
        $this->setFullName($model);
    }

    protected function setFullName(Admin $model)
    {
        $model->full_name = $model->first_name.' '.$model->last_name;
    }
}
