<?php

namespace App;

use App\Cacheable;
use App\History;
use App\Jobs\ReleaseRebates;
use App\Scopes\HasStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Application extends Model {
	use Addressable;
	use HasStatus;
	use ApprovedOrDenied;
	use Cacheable;

	const ST_NEW = 'new';
	const ST_PENDING_REVIEW = 'pending-review';
	const ST_EXPIRED = 'expired';
	const ST_APPROVED = 'approved';
	const ST_DENIED = 'denied';

	/**
	 * Valid values for the status column.
	 *
	 * Any other statuses referred to in the application are
	 * a "virtual" status. Meaning they are used as a term that applies
	 * to a combination of valid statuses, not as a concrete status.
	 */
	const VALID_STATUSES = [
		'new',
		'pending-review',
		'expired',
		'approved',
		'denied',
	];

	protected $dates = ['notification_sent_at', 'admin_first_viewed_at'];

	protected static function boot(): void{
		parent::boot();

		static::deleting(function ($model) {

			optional($model->applicant)->delete();

			optional($model->claim)->delete();

			optional($model->property)->delete();

			optional($model->transaction)->delete();

			optional($model->history)->delete();

			$model->comments->each( function ($comment) {
				$comment->delete();
			});

			if ($model->shouldClaimRebates()) {
				ReleaseRebates::dispatchNow($model);
			}

		});

		static::addScopes();
	}

	protected static function addScopes() {
		static::addGlobalScope('exclude_wait_listed', function (Builder $builder) {
			$builder->where('wait_listed', false);
		});
	}

	public static function getCached($id)
	{
		return Cache::remember('application_'.$id, $seconds = 10, function () use ($id) {
            return Application::find($id);
        });
	}

	/**
	 * Generate a code for a given application
	 * @param  Partner $partner - Pass the rebate partner
	 * @param  Carbon  $data   - Defaults to now
	 * @return string
	 */
	public static function generateUniqueCode(Partner $partner, Carbon $date = null): string{
		$prefix = $partner->account_key;
		$suffix = ( $date ?: Carbon::now() )->format('mdy');

		$codeFn = function ($index) use ($prefix, $suffix) {
			return $prefix . str_pad($index, 3, '0', STR_PAD_LEFT) . $suffix;
		};

		$existing = static::withoutGlobalScopes()->where('rebate_code', 'LIKE', $prefix . '%' . $suffix)->pluck('rebate_code');

		$index = 1;
		while ($existing->contains($codeFn($index))) {
			$index++;
		}

		return $codeFn($index);
	}

	/**
	 * Scope applications that qualify as "approved"
	 */
	public function scopeApproved($query): Builder {
		return $query->where('applications.status', self::ST_APPROVED);
	}

/**----

ALL APPLICATIONS THAT ARE USING UP A REBATE SHOULD FALL INTO ONE OF THE FOLLOWING:

---*/

	/**
	 * Scope applications that are new or pending
	 */
	public function scopePending($query): Builder {
		return $query->whereIn('applications.status', [self::ST_NEW, self::ST_PENDING_REVIEW]);
	}

	public function scopeHasClaimedRebates($query) {
		return $query->whereNotIn('applications.status', [self::ST_DENIED, self::ST_EXPIRED])
			->whereDoesntHave('claim', function ($query) {
				$query->denied(); // It is possible to have an approved application with a denied claim
			});
	}

	/**
	 * Scope applications whose claims are pending
	 */
	public function scopeHasPendingClaim($query): Builder {
		return $query
			->approved()
			->whereHas('claim', function ($query) {
				$query->pending();
			});
	}

	/**
	 * Scope applications whose claims are approved
	 */
	public function scopeHasApprovedClaim($query): Builder {
		return $query
			->approved()
			->whereHas('claim', function ($query) {
				$query->approved();
			});
	}

/**--------*->

/**
 * Applications with an applicant that checked
 * "feature on water saver"
 */
	public function scopeFeaturedOnWaterSaver($query): Builder {
		return $query->whereHas('applicant', function ($query) {
			$query->where('feature_on_water_saver', true);
		});
	}

	/**
	 * Note that any denied application should also have
	 * a transaction of type 'denied'.
	 */
	public function scopeDenied($query) {
		return $query->where('applications.status', self::ST_DENIED);
	}

	/**
	 * Applications new or pending review for over 14 days
	 */
	public function scopeSpecialAttention($query) {
		return $query
			->pending()
			->whereDate('created_at', '<', Carbon::parse('-2 weeks')->format('Y-m-d'))
		;
	}

	public function scopeOrderByTransactionDate($query, $sort = 'asc') {
		return $query
			->select('applications.*')
			->join('application_transactions', 'application_transactions.application_id', '=', 'applications.id')
			->orderBy('application_transactions.created_at', $sort);
	}

	protected function getValidStatuses(): array
	{
		return static::VALID_STATUSES;
	}

	public function rebate(): BelongsTo {
		return $this->belongsTo(Rebate::class);
	}

	public function applicant(): BelongsTo {
		return $this->belongsTo(Applicant::class);
	}

	public function claim(): HasOne {
		return $this->hasOne(Claim::class);
	}

	/**
	 * The approval or denial of the application
	 * @return null|Transaction
	 */
	public function transaction(): HasOne {
		return $this->hasOne(ApplicationTransaction::class);
	}

	/**
	 * The approval or denial of the application
	 * @return null|Transaction
	 */
	public function history(): HasOne {
		return $this->hasOne(History::class);
	}

	/**
	 * The address to send the rebate to.
	 *
	 * If the app has an address (remittance address) we use that,
	 * otherwise it will default to the property address.
	 */
	public function getMailToAddress() {
		if ($this->hasRemittanceAddress()) {
			return $this->address;
		}

		return optional($this->property)->address;
	}

	public function hasRemittanceAddress(): bool {
		return false === empty($this->address);
	}

	/**
	 * If this is not null then the Application
	 * was created by an admin and not an applicant.
	 *
	 * @return Admin|null
	 */
	public function admin(): BelongsTo {
		return $this->belongsTo(Admin::class);
	}

	public function property(): HasOne {
		return $this->hasOne(Property::class);
	}

	public function comments(): HasMany {
		return $this->hasMany(ApplicationComment::class);
	}

	public function submissionType(): string {
		return $this->admin ? 'staff' : 'online';
	}

	public function getSubmittedByText(): string {
		if ('staff' == $this->submissionType()) {
			return 'staff (' . $this->admin->full_name . ')';
		}

		return 'online';
	}

	public function isWaitListed(): bool {
		return true == $this->wait_listed;
	}

	public function isPendingReview(): bool {
		return $this->status == static::ST_PENDING_REVIEW;
	}

	public function isExpired(): bool {
		if ($this->claim && $this->claim->isExpired()) {
			return true;
		}

		# They should never fall out of sync but just in case
		return $this->status == static::ST_EXPIRED;
	}

	/**
	 * Status new or pending review
	 */
	public function isInReview(): bool {
		return false == $this->isDenied()
		&& false == $this->isApproved()
		&& false == $this->isWaitListed()
		;
	}

	public function isSpecialAttention(): bool {
		if (false === in_array($this->status, [self::ST_PENDING_REVIEW])) {
			return false;
		}

		if (empty($this->admin_first_viewed_at)) {
			throw new \RuntimeException('Application Pending Review but missing admin_first_viewed_at value');
		}

		$created = Carbon::parse($this->admin_first_viewed_at->format('Y-m-d 00:00:00'));
		$cutOff = Carbon::parse(Carbon::parse('-2 weeks')->format('Y-m-d 00:00:00'));

		return $created->lt($cutOff);
	}

	public function shouldClaimRebates(): bool {
		return false === $this->isWaitListed();
	}

	public function rebatesAreClaimable(): bool {
		return $this->rebate->canSatisfyApplication($this);
	}

	public function getApplication(): Application {
		return $this;
	}

	public function getApprovedOnAttribute() {
		return optional($this->transaction)->isApproved() ? $this->transaction->created_at : null;
	}

	public function getDeniedOnAttribute() {
		return optional($this->transaction)->isDenied() ? $this->transaction->created_at : null;
	}
}
