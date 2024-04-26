<?php

namespace App\Listeners;

use App\Address;
use App\History;

/**
 * Write the application to the history table at create time
 *
 * No need to queue, this is just a quick insert.
 *
 * There is only one reason to store records that are a few
 * years old in this application.
 *
 * The reason is the Quick Audit. The Quick Audit allows the rebate
 * admin to determine whether or not a person has already been rejected
 * for a rebate and is trying to apply again or scheme their way
 * into qualifying for the rebate based on their previous rejection.
 *
 * Keeping all the old records will clutter the database. We could
 * use query scopes to keep the UI clean and the queries still fairly quick
 * but we're still going to see some slow down if we don't get rid of these
 * old records. It's just nice to only keep what you need and will make performance
 * better and development more pleasant.
 *
 * We can accomplish the requirements of the quick audit and keep our DB fast & clean
 * by caching old records into a single table. In other words we can denormalize some data
 * and toss out the rest of it.
 *
 * This is somewhat like a "materialized view".
 *
 * An additional benefit of denormalizing is a significant speed increase
 * when running queries for the Quick Audit. We can now run these queries
 * without using any joins which are a bottleneck in SQL.
 */
final class WriteApplicationToHistory
{
    # App\Events\ApplicationWasCreated
    # App\Events\ApplicationWasImported
    public function handle($event): void
    {
        $a = $event->application;
        $address   = optional(optional($a->property)->address);
        $account   = optional(optional($a->property)->utilityAccount);
        $applicant = optional($a->applicant);

        try {
            History::create([
                'application_id' => $a->id,
                'line_one'       => $address->line_one ?? '',
                'line_two'       => $address->line_two,
                'city'           => $address->city ?? '',
                'state'          => $address->state ?? '',
                'postcode'       => $address->postcode ?? '',
                'account_number' => $account->account_number ?? '',
                'full_name'      => $applicant->full_name ?? '',
                'email'          => $applicant->email ?? '',
                'partner'        => $a->rebate->partner->name,
                'submitted_at'   => $a->created_at,
                'rebate_code'    => $a->rebate_code,
                'status'         => $a->status,
                'address_index'  => Address::buildIndex($address)
            ]);
        } catch (\Exception $e) {
            throw $e;
            \Log::error('Failed to write application '.$a->id. ' to history table');
        }
    }
}
