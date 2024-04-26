<?php

use Illuminate\Database\Seeder;

class DenialReasons extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            [
                'reason' => 'Funding Is No Longer Available',
                'message' => 'Funding Is No Longer Available',
            ],
            [
                'reason' => 'You Did Not Submit A Complete Application',
                'message' => 'You Did Not Submit A Complete Application'
            ],
            [
                'reason' => 'Did not respond to request for additional information',
                'message' => 'Hello, Unfortunately, since you did not respond to our requests for additional information we will have to deny this rebate. We encourage you to apply again if you are still interested in the program and funds are still available. Thank you.'
            ],
        ];

        $types = ['application', 'claim'];

        foreach ($types as $type) {
            collect($reasons)->each(function($reason) use ($type) {
                $reason['type'] = $type;
                factory(App\DenialReason::class)->create($reason);
            });
        }
    }
}
