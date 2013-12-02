<?php namespace Ipalaus\Geonames\Seeders;

class DevelopmentNamesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->names('geonames_names', $path . '/cities15000.txt');
	}

}