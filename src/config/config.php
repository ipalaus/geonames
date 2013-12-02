<?php

return array(

	'import' => array(

		'path' => storage_path() . '/meta/geonames',

		'files' => array(
			'countries' => 'http://download.geonames.org/export/dump/countryInfo.txt',
			'names'     => 'http://download.geonames.org/export/dump/allCountries.zip',
			'alternate' => 'http://download.geonames.org/export/dump/alternateNames.zip',
			'hierarchy' => 'http://download.geonames.org/export/dump/hierarchy.zip',
			'admin1'    => 'http://download.geonames.org/export/dump/admin1CodesASCII.txt',
			'admin2'    => 'http://download.geonames.org/export/dump/admin2Codes.txt',
			'feature'   => 'http://download.geonames.org/export/dump/featureCodes_en.txt',
			'timezones' => 'http://download.geonames.org/export/dump/timeZones.txt',
		),

		'development' => 'http://download.geonames.org/export/dump/cities15000.zip',

		'wildcard' => 'http://download.geonames.org/export/dump/%s.zip',
	),

);