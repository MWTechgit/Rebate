<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Any/all JSON routes should go here. If they are private routes
| put them in the route group that assigns the admin.can_write middleware.
|
| They all receive "api" middleware group by default and an "api" prefix to the URI
|
*/

# ===========================================================================================
# Public API Routes
# ===========================================================================================
# Technically not public as we should require a key for this.
# These routes will be accessed by the external form for submitting an application.
# The form is hosted on a different website (conservationpays.com)
Route::namespace('Api')->group(function() {
    # =======================================================================================
    # Submissions
    # =======================================================================================
    Route::name('api.submissions.store')->post('submissions', 'SubmissionsController@store');

    # =======================================================================================
    # Steps
    # =======================================================================================
    Route::name('api.steps.one')->post('steps/one', 'StepsController@one');
    Route::name('api.steps.two')->post('steps/two', 'StepsController@two');
    Route::name('api.steps.three')->post('steps/three', 'StepsController@three');
    Route::name('api.steps.four')->post('steps/four', 'StepsController@four');

    # =======================================================================================
    # Partners
    # =======================================================================================
    Route::name('api.partners.active')->get('/partners/active', 'PartnersController@active');

    # =======================================================================================
    # Reference Types
    # =======================================================================================
    Route::name('api.references_types.index')->get('/reference-types', 'ReferenceTypesController@index');
});
