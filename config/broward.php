<?php

return [
	'logo_url' => 'https://conservationpays.com/browards/assets/logo.png',

	# The number of years to keep applications before deleting them
	# and their related models. Some data is retained in history table
	# for purposes of the application quick audit.
	# \App\Listeners\WriteApplicationToHistory
	# \App\Console\Commands\PruneDb
	'years_to_store_full_applications' => 3,

	'prune_disabled' => env('PRUNE_DISABLED', false),

	'gmap_api_key' => env('GMAP_API_KEY', 'AIzaSyDxoV4U563gJJWuaypUw9qOpUorzunm--M'),

	'gmap_kml_url' => 'https://rebates.conservationpays.com/gmaps/bwp.kml',

	'contact' => [
		'phone' => '1-800-270-9794',
		'email' => 'ConservationPays@Broward.org',
		'url' => 'https://conservationpays.com/contact',
		'address' => 'EPCRD, 115 South Andrews Avenue, Room 329H, Fort Lauderdale, FL 33301',
	],

	't&c' => 'http://www.conservationpays.com/rebate-guidelines-terms-and-conditions/',

	'partner_url' => 'https://conservationpays.com/partners/',

	'homepage' => 'http://conservationpays.com/',

	'super_admins' => [
		'erinlambro@gmail.com',
		'stbaker@broward.org',

	],
];