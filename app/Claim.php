<?php

namespace App;

use App\Cacheable;
use App\Scopes\HasStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;

/**
 * A claim on a Rebate/Application
 *
 * After an Applicant submits an Application,
 * the application must be approved by an Admin.
 *
 * If the application is approved, the applicant
 * can then purchase the RebateItem.
 *
 * After purchasing the item, they finally make a Claim.
 *
 * In order to make a claim, the applicant must
 * submit a form containing their receipt, product upc code,
 * and a picture of the RebateItem installed on the property.
 *
 * Even after purchasing the item and submitting the documents
 * required to make the claim, the claim may be denied.
 *
 * Denial reasons include but are not limited to,
 * - submitting the claim past the deadline
 * - purchasing a toilet that does not qualify
 * - failure to respond to a request for additional info
 */
class Claim extends Model {
	use HasStatus;
	use ApprovedOrDenied;
	use Cacheable;

	# Default days until expiration
	# The expires_at column is populated
	# when the claim.application is approved.
	const EXPIRES_IN = '45';

	const EXPIRES_SOON = 14;

	# Use constants to reference claim statuses
	# to avoid bugs due to under the radar typos.
	const ST_UNCLAIMED = 'not-claimed';
	const ST_NEW = 'new';
	const ST_PENDING_REVIEW = 'pending-review';
	const ST_DENIED = 'denied';
	const ST_PENDING_FULFILLMENT = 'pending-fulfillment';
	const ST_FULFILLED = 'fulfilled';
	const ST_EXPIRED = 'expired';

	/**
	 * Valid values for the status column.
	 *
	 * Any other statuses referred to in the application are
	 * a "virtual" status. Meaning they are used as a term that applies
	 * to a combination of valid statuses, not as a concrete status.
	 *
	 * For example "approved" is any claim with status
	 * pending-fulfillment or fulfilled but is not a valid
	 * value for the status column.
	 */
	const VALID_STATUSES = [
		self::ST_UNCLAIMED,
		self::ST_NEW,
		self::ST_PENDING_REVIEW,
		self::ST_DENIED,
		self::ST_PENDING_FULFILLMENT,
		self::ST_FULFILLED,
		self::ST_EXPIRED,
	];

	protected $dates = [
		'submitted_at', 'expires_at', 'expired_at', 'post_marked_at',
	];

	protected static function boot(): void{
		parent::boot();

		static::deleting(function ($model) {

			$model->documentSets->each( function ($set) {
				$set->delete();
			});

			optional($model->transaction)->delete();

			$model->comments->each( function ($comment) {
				$comment->delete();
			});
		});
	}

	public static function getCached($id)
	{
		return Cache::remember('claim_'.$id, $seconds = 10, function () use ($id) {
            return Claim::find($id);
        });
	}

	/**
	 * Scope claims that qualify as "approved"
	 */
	public function scopeApproved($query) {
		return $query->whereIn(
			'claims.status',
			[self::ST_PENDING_FULFILLMENT, self::ST_FULFILLED]
		);
	}

	/**
	 * Scope claims that have an approved application but have not yet been approved, denied or expired
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public function scopePending($query) {
		return $query->whereIn('claims.status', [
			self::ST_UNCLAIMED,
			self::ST_NEW,
			self::ST_PENDING_REVIEW,
		]);
	}

	/**
	 * Unclaimed claims are claims that have not yet reached
	 * final submission but have an approved application.
	 *
	 * In other words, the persons application was approved and they
	 * either haven't started on or haven't finished their claim submission yet.
	 */
	public function scopeUnclaimed($query) {
		return $query
			->whereNotIn('claims.status', [self::ST_PENDING_FULFILLMENT, self::ST_FULFILLED, self::ST_DENIED])
			->whereHas('application.transaction', function ($query) {
				$query->where('application_transactions.type', ApplicationTransaction::ST_APPROVED);
			});
		// @todo - Justin joined tables instead of querying laravel relationships.
		// This overwrites duplicate column names so that 'id', 'created_at', etc are filled
		// with incorrect values. It also showed denied and fullfilled claims as 'unclaimed'

		// Justin's join:
		// ->leftJoin('applications', 'applications.id', '=', 'claims.application_id')
		// ->leftJoin('application_transactions', 'applications.id', '=', 'application_transactions.application_id')
		// ->where('application_transactions.type', ApplicationTransaction::ST_APPROVED)
		;
	}

	/**
	 * Approved
	 *
	 * The claim is pending-fulfillment after
	 * the admin has approved the claim.
	 */
	public function scopePendingExport($query) {
		return $query->where('claims.status', self::ST_PENDING_FULFILLMENT);
	}

	/**
	 * Note that any denied claim should also have
	 * a transaction of type 'denied'.
	 */
	public function scopeDenied($query) {
		return $query->where('claims.status', self::ST_DENIED);
	}

	/**
	 * Claims mailed in will have a post marked at date
	 */
	public function scopeIgnoreMailed($query) {
		return $query->whereNull('post_marked_at');
	}

	public function scopeIgnoreExpired($query) {
		return $query->whereNull('claims.expired_at');
	}

	public function scopeExpiringSoon($query) {
		return $query->ignoreMailed()
			->ignoreExpired()
			->whereDate('claims.expires_at', '<=', now()->addDays(static::EXPIRES_SOON)->format('Y-m-d'));
		// ->whereDate('claims.expires_at', '>=', now()->format('Y-m-d'))
		;
	}

	public function scopeExpired($query) {
		return $query->whereNotNull('claims.expired_at');
	}

	public function scopeWhereNotAncient($query) {
		return $query->where('claims.fy_year', '>', fiscal_year()-2);
	}

	public function isNotAncient()
	{
		return $this->fy_year >= fiscal_year()-2;
	}

	/**
	 * Claims have an "expired_at" field that is used by a scheduled
	 * job to go through and expire all claims that should be expired.
	 * Claims aren't officially expired until the job runs on the claim.
	 */
	public function scopeExpiredRecently($query) {
		return $query->whereDate('claims.expired_at', '>=', now()->subDays(static::EXPIRES_SOON)->toDateString());
	}

	/**
	 * Claims that have an "expires_at" value <= now
	 * and are not already expired.
	 */
	public function scopeShouldBeExpired($query) {
		return $query
			->unclaimed()
			->ignoreExpired()
			->pastScheduledExpiration()
		;
	}

	public function scopePastScheduledExpiration($query) {
		return $query->where('claims.expires_at', '<=', now());
	}

	public function scopeOrderByTransactionDate($query, $sort = 'asc') {
		return $query
			->select('claims.*')
			->leftJoin('claim_transactions', 'claim_transactions.claim_id', '=', 'claims.id')
			->orderBy('claim_transactions.created_at', $sort);
	}

	public function scopeOrderByApplicationTransactionDate($query, $sort = 'asc') {
		return $query
			->select('claims.*')
			->join('application_transactions', 'claims.application_id', '=', 'application_transactions.application_id')
			->whereNotNull('application_transactions.created_at')
			->orderBy('application_transactions.created_at', $sort);
	}

	protected function getValidStatuses(): array
	{
		return static::VALID_STATUSES;
	}

	public function rebate(): Rebate {
		return $this->application->rebate;
	}

	public function rebateCount(): int {
		return $this->application->rebate_count;
	}

	public function rebateValue(): float {
		return $this->rebate()->value;
	}

	public function maxValue(): float{
		$amount = $this->rebateValue() * $this->rebateCount();
		return (float) $amount;
	}

	public function applicant(): BelongsTo {
		return $this->belongsTo(Applicant::class);
	}

	public function application(): BelongsTo {
		return $this->belongsTo(Application::class);
	}

	public function comments(): HasMany {
		return $this->hasMany(ClaimComment::class);
	}

	/**
	 * The approval or denial of the claim
	 *
	 * @return null|Transaction
	 */
	public function transaction(): HasOne {
		return $this->hasOne(ClaimTransaction::class);
	}

	public function documentSets(): HasMany {
		return $this->hasMany(DocumentSet::class);
	}

	/**
	 * Status pending review
	 */
	public function isInReview(): bool {
		return $this->status === static::ST_PENDING_REVIEW;
	}

	public function isNew(): bool {
		return $this->status === static::ST_NEW;
	}

	public function isUnclaimed(): bool {
		return $this->status === static::ST_UNCLAIMED;
	}

	public function hasRequiredFiles(): bool {
		return $this->skip_document_upload || $this->allFilesUploaded();
	}

	public function numFiles(): int {
		return $this->documentSets->reduce(function ($carry, $item) {
			return $item->numFiles() + $carry;
		}, 0);
	}

	public function expectedFileCount(): int {
		return count(DocumentSet::FILES) * $this->expectedSetCount();
	}

	public function expectedSetCount(): int {
		return $this->rebateCount();
	}

	public function allFilesUploaded(): bool{
		$rebateCount = $this->application->rebate_count;

		if ($this->documentSets()->count() < $rebateCount) {
			return false;
		}

		return $this->documentSets->every(function ($set) {
			return $set->hasAllFiles();
		});
	}

	public function isExpired(): bool {
		return false === empty($this->expired_at);
	}

	public function getRebateCodeAttribute() {
		return optional($this->application)->rebate_code;
	}

	public function getApprovedOnAttribute() {
		return optional($this->transaction)->isApproved() ? $this->transaction->created_at : null;
	}

	public function getDeniedOnAttribute() {
		return optional($this->transaction)->isDenied() ? $this->transaction->created_at : null;
	}

	public function getApplicationStatusAttribute() {
		return optional($this->application)->status;
	}

	public function getApplicationApprovedOnAttribute() {
		return optional($this->application)->approved_on;
	}

	public function getAmountAwardedAttribute($value) {
		return (float) is_string($value) ? str_replace(',', '', $value) : $value;
	}

	public function getApplication(): Application {
		return $this->application;
	}
}
