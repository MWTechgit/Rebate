<?php

namespace App\Http\Controllers\Admin;

use App\ExportBatch;
use App\Exports\ExportBatchExport;
use App\Exports\ExportClaimPropertyList;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ExportBatchController extends Controller {
	public function __construct() {
		$this->middleware('admin.can_write');
	}

	public function downloadAndDelete(Request $request, ExportBatch $exportBatch) {

		$response = Excel::download(new ExportBatchExport($exportBatch), $exportBatch->filename ?? 'download.xlsx');

		$exportBatch->delete();

		return $response;
	}

	public function download(Request $request, ExportBatch $exportBatch) {

		return Excel::download(new ExportBatchExport($exportBatch), $exportBatch->filename ?? 'download.xlsx');

	}

	public function downloadPropertyList(Request $request, ExportBatch $exportBatch) {

		return Excel::download(new ExportClaimPropertyList($exportBatch), Carbon::now()->format('Y-m-d') . '_claims-property-list.csv');

	}

}
