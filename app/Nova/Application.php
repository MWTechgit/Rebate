<?php

namespace App\Nova;

use Bwp\ApplicationActions\ApplicationActions;
use Bwp\ApplicationComments\ApplicationComments;
use Bwp\QuickAudit\QuickAudit;
use Bwp\ReviewApplication\ReviewApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphOne;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Application extends Resource {

	use Application\IndexFields;
	use Application\DetailFields;
	use Application\UpdateFields;

	const STATUS_OPTIONS = ['new' => 'New', 'pending-review' => 'Pending Review'];

	public static $model = 'App\Application';

	public static $group = 'Applications';

	public static $with = ['applicant.reference', 'rebate', 'property.utilityAccount', 'property.address', 'address'];

	public static $title = 'rebate_code';

	public static $search = ['rebate_code'];

	public static $searchRelations = [
		'applicant' => ['full_name', 'email', 'first_name', 'last_name', 'company'],
		// 'property.address' => ['line_one']
	];

	public static function getDefaultOrderBy(NovaRequest $request, $query) {
		return $query->orderByRaw("FIELD(status, 'new', 'pending-review'), created_at");
	}

	/**
	 * The global search result subtitle for the resource.
	 */
	public function subtitle() {
		return "Applicant: {$this->applicant->full_name}";
	}

	public function fullPageSearchTitle() {
		return $this->rebate_code . " --- " . optional($this->applicant)->full_name;
	}

	public function fullPageSearchSubtitle() {
		return optional(optional($this->property)->address)->full;
	}

	public function fields(Request $request) {

		if ($this->isRebateDetail()) {
			return $this->rebateFields();
		}

		return [
			QuickAudit::make(),

			# Index Only
			$this->merge($this->getIndexFields()),

			# Update Only
			$this->merge($this->getUpdateFields($request)),

			# Detail Only
			ReviewApplication::make()->withToolbar(),

			# Approve/Deny Form
			ApplicationActions::make(),

			# Link to attached claim
			$this->mergeWhen($this->isApproved(), function () {
				return [HasOne::make('Claim')];
			}),

			ApplicationComments::make(),

			MorphOne::make('Address')->nullable()->onlyOnForms(), // $this->hasRemittanceAddress(), addressable. Keep this or the remittance forms wont work


		];
	}

	public function cards(Request $request) {
		return [];
	}

	public function filters(Request $request) {
		return [
			new \App\Nova\Filters\ApplicationStatus,
			new \App\Nova\Filters\ApplicationPartner,
			new \App\Nova\Filters\FiscalYear,
			new \App\Nova\Filters\DateApplicationSubmitted
		];
	}

	public function lenses(Request $request) {
		return [
			new Lenses\ApplicationInbox,
			new Lenses\ApprovedApplications,
			new Lenses\DeniedApplications,
			new Lenses\SpecialAttention(static::newModel()),
			new Lenses\WaterSavers(static::newModel()),
		];
	}

	public function actions(Request $request) {
		return [
			(new Actions\MarkAsNew)->canSee(function ($request) {
				return $request->user()->canWrite();
			}),
			(new Actions\SendSpecialAttentionNotification)->canSee(function ($request) {
				return $request->user()->canWrite();
			}),
			(new Actions\ChangeRebateCount)->canSee(function ($request) {
				return $request->user()->canWrite();
			}),
			(new Actions\ChangeRebate)->canSee(function ($request) {
				return $request->user()->canWrite();
			}),
			(new Actions\ExportApplicants)->canSee(function ($request) {
				return $request->user()->canWrite();
			}),
			(new Actions\ExportAll)->canSee(function ($request) {
				return $request->user()->canWrite();
			})
		];
	}

	protected function isRebateDetail() {
		return Str::contains(url()->full(), 'viaResource=rebates');
	}

	protected function rebateFields() {
		return [
			ID::make()->sortable()->onlyOnIndex(),

			Text::make('Applicant', function () {
				if (empty($this->applicant)) {
					return;
				}

				return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/applications/' . $this->id . '">' . $this->applicant->full_name . '</a>';
			})->asHtml(),

			Text::make('Application Number', 'rebate_code')
				->metaReadOnly(),

			Number::make('Rebates', 'rebate_count'),

			Currency::make('Amount Awarded', 'claim.amount_awarded')->currency('USD')->nullable(),

			Text::make('App Status', 'status'),

			Text::make('Claim Status', 'claim.status'),
		];
	}
}
