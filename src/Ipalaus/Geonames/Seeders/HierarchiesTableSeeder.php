<?php namespace Ipalaus\Geonames\Seeders;

class HierarchiesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->hierarchies('geonames_hierarchies', $path . '/hierarchy.txt');
	}

}