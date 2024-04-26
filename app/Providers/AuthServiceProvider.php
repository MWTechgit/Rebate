<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Address::class => \App\Policies\AddressPolicy::class,
        \App\Admin::class => \App\Policies\AdminPolicy::class,
        \App\Applicant::class => \App\Policies\ApplicantPolicy::class,
        \App\Application::class => \App\Policies\ApplicationPolicy::class,
        \App\ApplicationTransaction::class => \App\Policies\ApplicationTransactionPolicy::class,
        \App\Claim::class => \App\Policies\ClaimPolicy::class,
        \App\ClaimTransaction::class => \App\Policies\ClaimTransactionPolicy::class,
        \App\DenialReason::class => \App\Policies\DenialReasonPolicy::class,
        \App\DocumentSet::class => \App\Policies\DocumentSetPolicy::class,
        \App\ExportBatch::class => \App\Policies\ExportBatchPolicy::class,
        \App\Owner::class => \App\Policies\OwnerPolicy::class,
        \App\Partner::class => \App\Policies\PartnerPolicy::class,
        \App\Property::class => \App\Policies\PropertyPolicy::class,
        \App\Rebate::class => \App\Policies\RebatePolicy::class,
        \App\RebateType::class => \App\Policies\RebateTypePolicy::class,
        \App\UtilityAccount::class => \App\Policies\UtilityAccountPolicy::class,
        \App\WaitListedApplication::class => \App\Policies\WaitListedApplicationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
