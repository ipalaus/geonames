<?php namespace Ipalaus\Geonames\Seeders;

class NamesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$continents = array(
			array('AF', 'Africa', 6255146),
			array('AS', 'Asia', 6255147),
			array('EU', 'Europe', 6255148),
			array('NA', 'North America', 6255149),
			array('OC', 'Oceania', 6255151),
			array('SA', 'South America', 6255150),
			array('AN', 'Antarctica', 6255152),
		);

		$this->importer->continents('geonames_continents', $continents);
	}

}