<?php

namespace App\Nova;

use App\Claim as Model;
use App\Nova\Actions\ExportPendingClaims;
use App\Nova\Actions\TestExport;
use Bwp\ClaimActions\ClaimActions;
use Bwp\ClaimAlerts\ClaimAlerts;
use Bwp\ClaimComments\ClaimComments;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Claim extends Resource {
	public static $model = 'App\Claim';

	public static $group = 'Claims';

	public static $with = ['application', 'applicant', 'transaction'];

	public static $title = 'id';

	public static $search = ['status', 'id'];

	/**
	 * See titasgailius/search-relations
	 * @var array
	 */
	public static $searchRelations = [
		'applicant' => ['full_name', 'email', 'first_name', 'last_name', 'company'],
		'application' => ['rebate_code'],
	];

	public static function getDefaultOrderBy(NovaRequest $request, $query) {
		return $query->orderBy('submitted_at', 'desc');
	}

	/**
	 * The global search result subtitle for the resource.
	 */
	public function subtitle() {
		return "Applicant: " . optional($this->applicant)->full_name . ", " . optional($this->application)->rebate_code;
	}

	public function fullPageSearchTitle() {
		return optional($this->application)->rebate_code . " --- " . optional($this->applicant)->full_name;
	}

	public function fullPageSearchSubtitle() {
		return optional(optional(optional($this->application)->property)->address)->full;
	}

	public function fields(Request $request) {

		if ($this->isExportBatchDetail()) {
			return $this->exportBatchFields();
		}

		return [
			ID::make()->sortable()->onlyOnIndex(),

			ClaimAlerts::make(),

			(new Panel('Application Details', $this->applicationFields()))->withToolbar(),

			(new Panel('Claim Details', $this->claimFields($request))),

			(new Panel('Rebate Details', $this->rebateFields())),

			ClaimActions::make(),

			HasMany::make('Document Sets', 'documentSets', 'App\Nova\DocumentSet'),

			ClaimComments::make(),

		];
	}

	public function cards(Request $request) {
		return [];
	}

	public function filters(Request $request) {
		return [
			new \App\Nova\Filters\ClaimPartner,
			new \App\Nova\Filters\ClaimStatus,
			new \App\Nova\Filters\FiscalYear,
			new \App\Nova\Filters\ReferenceType,
            new \App\Nova\Filters\DateClaimSubmitted,
		];
	}

	public function lenses(Request $request) {
		return [
			new Lenses\NewClaims,
			new Lenses\ApprovedClaims,
			new Lenses\DeniedClaims,
			new Lenses\Unclaimed,
			new Lenses\PendingExport,
			new Lenses\ExpiringSoon,
			new Lenses\ExpiredRecently,
			new Lenses\Referrals,
		];
	}

	public function actions(Request $request) {

		return [
			(new Actions\RestoreExpiredClaim)->canSee(function ($request) {
				return $request->user()->canWrite();
			}),
			(new Actions\ChangeRebate)->canSee(function ($request) {
				return $request->user()->canWrite();
			}),
			(new ExportPendingClaims)
				->canSee(function ($request) {
					return $request->user()->canWrite();
				})
				// ->askForWriterType()
				->withFilename(Carbon::now()->format('Y-m-d') . '_claims-pending-export.csv'),

			(new TestExport)
				->canSee(function ($request) {
					return $request->user()->canWrite();
				})
				->withFilename(Carbon::now()->format('Y-m-d') . '_TEST.csv'),
			(new Actions\ExportClaimAll)->canSee(function ($request) {
				return $request->user()->canWrite();
			})
		];
	}

	protected function claimFields(Request $request) {
		return [
			Select::make('Submission Type')->options([
				'online' => 'Online',
				'mail' => 'Mail',
				'email' => 'E-Mail',
			])->displayUsingLabels()
				->hideFromIndex()
			/*->rules('required')*/,

			DateTime::make('Submitted On', 'submitted_at')
			// ->rules('required')
				->format('MMM D, Y h:mma')
				->sortable(),

			Number::make('Fiscal Year', 'fy_year')
				->metaReadOnly()
				->help('Use the "Change Rebate" action to change the fiscal year'),

			Date::make('Expires', 'expires_at')
				->format('MMM D, Y')
				->onlyOnDetail(),

			# Only edit the expiration date if claim not expired
			Date::make('Expires', 'expires_at')
				->format('MMM D, Y')
				->hideFromIndex()
				->hideFromDetail()
				->canSee(function ($request) {
					return false === $this->isExpired();
				}),

			Boolean::make('Expiring Soon Notification Sent', 'expire_notification_sent')
				->help('Change this only if you want to change the default behavior of this notification. Check the box to prevent the email from being sent. Uncheck the box to send the email again within ' . Model::EXPIRES_SOON . ' days of the expiration date')
				->hideFromIndex(),

			Text::make('Status')->hideWhenUpdating(),

			Currency::make('Amount Awarded', 'amount_awarded')->currency('USD')->nullable(),
			// ->onlyOnDetail(),
		];
	}

	protected function rebateFields() {
		return [
			Text::make('Rebate', function () {
				return view('nova.partials.rebate-link', [
					'application' => $this->application,
				])->render();
			})
				->asHtml()
				->onlyOnDetail(),

			Text::make('Partner', 'application.rebate.partner.name')
				->onlyOnDetail(),

			Text::make('Fiscal Year', 'application.rebate.fy_year')
				->onlyOnDetail(),

			Currency::make('Max value per rebate', 'application.rebate.value')->currency('USD')
				->onlyOnDetail()->nullable(),

			Number::make('Rebates applied for', 'application.rebate_count')
				->onlyOnDetail(),

			Number::make('Additional Desired', 'application.desired_rebate_count')
				->onlyOnDetail(),

			Text::make('Rebates Remaining', 'application.rebate.remaining')
				->onlyOnDetail(),
		];
	}

	protected function applicationFields() {
		return [
			BelongsTo::make('Applicant')
				->onlyOnDetail(),

			Text::make('Applicant', function () {
				if (empty($this->applicant)) {
					return;
				}
				return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/claims/' . $this->id . '">' . $this->applicant->full_name . '</a>';
			})->asHtml()->onlyOnIndex(),

			# You can't change the applicant that the claim belongs to
			Text::make('Applicant', 'applicant.full_name')
				->onlyOnForms()
				->metaReadOnly(),

			Text::make('Email', function () {
				return optional($this->applicant)->email ? '<a href="mailto:' . $this->applicant->email . '">' . $this->applicant->email . '</a>' : '';
			})
				->asHtml()
				->onlyOnDetail(),

			BelongsTo::make('Application')
				->hideWhenUpdating(),

			# You can't change the application the claim belongs to
			Text::make('Application', 'application.rebate_code')
				->hideFromIndex()
				->hideFromDetail()
				->metaReadOnly(),

			# Only approved applications have claims
			DateTime::make('Application approved on', 'application.transaction.created_at')
				->format('MMM D, Y h:mma')
				->onlyOnDetail(),
		];
	}

	protected function isExportBatchDetail() {
		return Str::contains(url()->full(), 'viaResource=export-batches');
	}

	protected function exportBatchFields() {
		return [
			ID::make()->sortable()->onlyOnIndex(),

			Text::make('Applicant', function () {
				if (empty($this->applicant)) {
					return;
				}

				return '<a class="no-underline dim text-primary font-bold" href="/admin/resources/applications/' . $this->application_id . '">' . $this->applicant->full_name . '</a>';
			})->asHtml(),

			Text::make('Address', function () {
				return (string) optional(optional($this->application)->property)->address;
			}),

			Currency::make('Amount Awarded', 'amount_awarded')->currency('USD')->nullable(),
		];
	}
}
