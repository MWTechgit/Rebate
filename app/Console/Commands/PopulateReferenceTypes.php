<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use App\ReferenceType;

class PopulateReferenceTypes extends Command
{
    protected $config;

    protected $signature = 'populate:reference-types';

    protected $description = 'Seed the reference types';

    public function __construct(Repository $config)
    {
        parent::__construct();

        $this->config = $config;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $types = $this->config->get('reference_types.types');

        foreach ($types as $type => $infoText) {

            $exists = ReferenceType::where('type', $type)->exists();

            if ($exists) {
                $this->comment("ReferenceType ($type) already exists. Skipped.");
                continue;
            }

            $newType = ReferenceType::create([
                'type' => $type,
                'info_text' => $infoText,
            ]);

            $this->info("ReferenceType ($type) created!");
        }
    }
}
