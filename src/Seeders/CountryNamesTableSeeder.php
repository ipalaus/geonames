<?php namespace Ipalaus\Geonames\Seeders;

class CountryNamesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path    = $this->command->option('path');
		$country = $this->command->option('country');

		$this->importer->names('geonames_names', $path . '/' . $country . '.txt');
	}

}