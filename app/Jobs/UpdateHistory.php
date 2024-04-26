<?php

namespace App\Jobs;

use App\Address;
use App\Application;
use App\Claim;
use App\History;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesJobs;

final class UpdateHistory
{
    use DispatchesJobs;

    /**
     * [$limit description]
     * @var 
     */
    protected $limit = 50;

    public function __construct($limit=null)
    {
        if ( $limit ) {
            $this->limit = $limit;
        }
    }

    public function handle()
    {
        $history = History::with(['application.claim', 'application.property.address', 'application.applicant'])
            ->select('history.*')
            ->join('applications', 'applications.id', '=', 'history.application_id')
            ->whereNotNull('applications.id')
            ->whereColumn('history.updated_at', '<', 'applications.updated_at')
            ->orderByDesc('applications.created_at')
            ->limit($this->limit)
            ->get();

        $history->each( function ($item) {
            
            try {

                $a = $item->application;
                $address = optional($a->property)->address;
                $account = optional($a->property)->utilityAccount;
                $applicant = $a->applicant;

                if ( $address ) {
                    $item->fill([
                        'line_one'       => $address->line_one,
                        'line_two'       => $address->line_two,
                        'city'           => $address->city,
                        'state'          => $address->state,
                        'postcode'       => $address->postcode
                    ]);
                    $item->address_index = Address::buildIndex($address);
                }

                $item->fill([
                    'account_number' => optional($account)->account_number ?: 'Unknown',
                    'full_name'      => optional($applicant)->full_name,
                    'email'          => optional($applicant)->email,
                    'status'         => $a->status,
                ])->save();

            } catch (\Exception $e) {
                throw $e;
                \Log::error('Failed to write application '.$a->id. ' to history table');
            } catch (\ErrorException $e) {
                throw $e;
                \Log::error('Failed to write application '.$a->id. ' to history table');
            }
        });

        echo \Log::notice($history->count() . ' history items updated.');
    }

}
