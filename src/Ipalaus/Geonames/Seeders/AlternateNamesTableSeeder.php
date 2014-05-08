<?php namespace Ipalaus\Geonames\Seeders;

class AlternateNamesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->alternateNames('geonames_alternate_names', $path . '/alternateNames.txt');
	}

}