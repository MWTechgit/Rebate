<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportApplicants;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExportApplicantController extends Controller {

	public function __construct() {
		$this->middleware('admin.can_write');
	}

	public function download(Request $request, $fy_year) {

		return (new ExportApplicants($fy_year) )->download('applicants.xlsx');

	}

}
