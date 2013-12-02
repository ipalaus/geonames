<?php namespace Ipalaus\Geonames\Seeders;

class TimezonesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->timezones('geonames_timezones', $path . '/timeZones.txt');
	}

}