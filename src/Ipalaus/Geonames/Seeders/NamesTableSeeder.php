<?php namespace Ipalaus\Geonames\Seeders;

class NamesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->names('geonames_names', $path . '/allCountries.txt');
	}

}