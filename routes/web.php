<?php

# =======================================================================================
# ROOT
# =======================================================================================
Route::redirect('/', 'login');

# =======================================================================================
# PUBLIC - APPLICANT LOGIN/LOGOUT
# =======================================================================================
Route::name('logout')->get('/logout', 'Applicant\LoginController@logout');

Route::middleware(['guest:applicant'])->namespace('Applicant')->group(function () {
	Route::name('login')->get('/login', 'LoginController@getLogin');
	Route::post('/login', 'LoginController@postLogin');
});

# =======================================================================================
# AUTH APPLICANT - APPLICATIONS & CLAIMS
# =======================================================================================
Route::middleware(['auth:applicant', 'is_applicant'])->namespace('Applicant')->group(function () {
	# APPLICATIONS
	Route::name('applications.status')->get('/applications/{application}/status', 'ApplicationsController@status');
	Route::name('applications.show')->get('/applications/{application}', 'ApplicationsController@show');

	# CLAIMS
	Route::name('claims.status')->get('/claims/{claim}/status', 'ClaimsController@status');
	Route::name('claims.edit')->get('/claims/{claim}/edit', 'ClaimsController@edit');
	Route::name('claims.update')->put('/claims/{claim}', 'ClaimsController@update');
	Route::name('claims.show')->get('/claims/{claim}', 'ClaimsController@show');

	# Final Submission
	Route::name('claims.submit')->post('/claims/{claim}/submit', 'ClaimsController@submit');

});

# ===========================================================================================
# Private API Routes (admin w/ write privs only)
# ===========================================================================================
Route::namespace ('Api')->middleware(['admin'])->group(function () {
	# =======================================================================================
	# Applications
	# =======================================================================================
	Route::name('api.applications.show')->get('/api/applications/{application}', 'ApplicationsController@show');
	Route::name('api.applications.next')->get('/api/applications/{application}/next/{lens?}', 'ApplicationsController@next');
	# =======================================================================================
	# Claims
	# =======================================================================================
	Route::name('api.claims.next')->get('/api/claims/{claim}/next/{lens?}', 'ClaimsController@next');
	Route::name('api.claims.show')->get('/api/claims/{claim}', 'ClaimsController@show');
});

# =======================================================================================
# JSON ADMIN WRITE
# =======================================================================================
// All of these routes should be prefixed with something
// They could eventually conflict with HTML routes like appplications/show (above)
Route::middleware(['admin'])->namespace('Admin\Json')->group(function () {
	# ===================================================================================
	# Denial Reasons
	# ===================================================================================
	Route::name('denials_reasons.index')->get('/denial-reasons', 'DenialReasonsController@index');

	# ===================================================================================
	# Claims
	# ===================================================================================
	Route::name('claims.awardable')->get('/claims/{claim}/awardable', 'ClaimsController@awardable');

	# ===================================================================================
	# Claim Transactions - Approve/Deny Claims
	# ===================================================================================
	Route::name('claims.approve')->post('/claims/{claim}/approve', 'ClaimTransactionsController@approve')
		->middleware('admin.can_write');
	Route::name('claims.deny')->post('/claims/{claim}/deny', 'ClaimTransactionsController@denyClaim')
		->middleware('admin.can_write');

	# ===================================================================================
	# Application Transactions - Approve/Deny Applications
	# ===================================================================================
	Route::name('applications.approve')->post('/applications/{application}/approve', 'ApplicationTransactionsController@approve')
		->middleware('admin.can_write');
	Route::name('applications.deny')->post('/applications/{application}/deny', 'ApplicationTransactionsController@denyApplication')
		->middleware('admin.can_write');

	# =======================================================================================
	# ApplicationComments
	# =======================================================================================
	Route::resource('applications.comments', 'ApplicationCommentsController')
		->only(['index', 'store', 'update', 'show', 'destroy']);

	# =======================================================================================
	# ClaimComments
	# =======================================================================================
	Route::resource('claims.comments', 'ClaimCommentsController')
		->only(['index', 'store', 'update', 'show', 'destroy']);

});

# =======================================================================================
# JSON ADMIN WRITE
# =======================================================================================
// All of these routes should be prefixed with something
// They could eventually conflict with HTML routes like appplications/show (above)
Route::middleware(['admin'])->namespace('Admin')->group(function () {

	# =======================================================================================
	# ExportBatches
	# =======================================================================================
	Route::get('/export_batches/download/{export_batch}', 'ExportBatchController@download')->name('export_batches.download');

	Route::get('/export_batches/downloadAndDelete/{export_batch}', 'ExportBatchController@downloadAndDelete')->name('export_batches.downloadAndDelete');

	Route::get('/export_batches/downloadPropertyList/{export_batch}', 'ExportBatchController@downloadPropertyList')->name('export_batches.downloadPropertyList');

	# =======================================================================================
	# ExportApplicants
	# =======================================================================================
	Route::get('/export_applicants/download/{fy_year}', 'ExportApplicantController@download')->name('export_applicants.download');

});

# =======================================================================================
# DEV MAIL PREVIEW
# =======================================================================================
if (false === app()->environment('production')) {

	Route::get('/preview/application-pending-review', 'DevController@pending');

	Route::get('/preview/application-wait-listed', 'DevController@waitlist');

	Route::get('/preview/application-special-attention', 'DevController@special');

	Route::get('/preview/claim-approved', 'DevController@approved');

	Route::get('/preview/claim-denied', 'DevController@denied');
}

# =======================================================================================
# DEV AUTH TEST
# =======================================================================================
Route::middleware(['super_admin'])->prefix('super-admin')->group(function () {

	Route::name('login_as')->get('/login-as/{status}', 'DevController@adminAs');

});
