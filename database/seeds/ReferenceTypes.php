<?php

use Illuminate\Database\Seeder;
use App\ReferenceType;

class ReferenceTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Website'                => 'What website?',
            'Utility bill message'   => null,
            'Newspaper Article'      => 'What newspaper?',
            'Home Improvement Store' => null,
            'Event'                  => 'What was the name of the event?',
            'Flyer'                  => 'Where did you see the flyer?',
            'Plumber/Contractor'     => 'What is the name of the individual/company?',
            'Business Card'          => null,
            'Bus Wrap'               => null,
            'Display/Poster'         => 'What display location?',
            'Advertisement'          => 'What advertisement?',
            'Workshop'               => 'What location?',
            'HOA'                    => 'HOA name?',
            'Friend/Family member'   => 'Friend/Family name?',
            'Neighbor'               => "Neighbor's name?",
            'Facebook'               => null,
            'Twitter'                => null,
            'Stickie Note'           => null,
            'Other'                  => 'Other?',
        ];

        foreach ($types as $type => $infoText) {
            factory(ReferenceType::class)->create([
                'type'      => $type,
                'info_text' => $infoText,
            ]);
        }
    }
}
