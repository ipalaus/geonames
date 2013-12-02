<?php namespace Ipalaus\Geonames\Seeders;

class CountriesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->countries('geonames_countries', $path . '/countryInfo.txt');
	}

}