<?php namespace Ipalaus\Geonames\Seeders;

class LanguageCodesTableSeeder extends DatabaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = $this->command->option('path');

		$this->importer->languageCodes('geonames_language_codes', $path . '/iso-languagecodes.txt');
	}

}