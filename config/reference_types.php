<?php

/**
 * Reference types are referral types
 *
 * The key is the type.
 *
 * The value is the text that should be used
 * for the html form label when asking additional
 * info on the reference type.
 *
 * Not all types require additional info.
 *
 * DB Table: reference_types
 * type: string
 * info_text: string
 *
 * types have many references
 * references belong to applicants
 */
return [
    'types' => [
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
    ],
];