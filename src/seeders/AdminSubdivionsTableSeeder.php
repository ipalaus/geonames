<?php namespace Ipalaus\Geonames\Seeders;

class AdminSubdivionsTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->adminDivions('geonames_admin_subdivisions', $path . '/admin1CodesASCII.txt');
	}

}