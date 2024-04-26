<?php

namespace App\Policies;

use App\Admin as AuthAdmin;
use App\ExportBatch;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policies defined here automatically determine
 * what users can do in the nova application.
 */
class ExportBatchPolicy {
	use HandlesAuthorization;

	public function view(AuthAdmin $authAdmin, ExportBatch $batch): bool {
		return $authAdmin->canWrite();
	}

    public function viewAny(AuthAdmin $admin)
    {
        return true;
    }

	public function create(AuthAdmin $authAdmin): bool {
		return false;
	}

	public function update(AuthAdmin $authAdmin, ExportBatch $batch): bool {
		return $authAdmin->canWrite();
	}

	public function delete(AuthAdmin $authAdmin, ExportBatch $batch): bool {
		return $authAdmin->canWrite();
	}

	public function restore(AuthAdmin $authAdmin, ExportBatch $batch): bool {
		return $authAdmin->canWrite();
	}

	public function forceDelete(AuthAdmin $authAdmin, ExportBatch $batch): bool {
		return $authAdmin->canWrite();
	}
}
