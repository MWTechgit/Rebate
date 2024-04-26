<?php

namespace App\Console\Commands;

use App\Application;
use App\Claim;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FillSubmissionType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:submission_type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move submission types to applications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Claim::whereNotNull('submission_type')->chunk(50, function ($claims) {
            $claims->each(function($claim) {
                DB::table('applications')
                    ->where('id', $claim->application_id)
                    ->update(['submission_type' => $claim->submission_type]);   
            });
            $this->info('Chunk completed.');
        });
        $this->info('Finished claim info.');

        $launch = Carbon::create('2019-07-22 00:00:00');
        DB::table('applications')
            ->whereNull('submission_type')
            ->where('created_at', '>=', $launch)
            ->update(['submission_type' => 'online']);

        $this->info('Finished application info.');
    }
}
