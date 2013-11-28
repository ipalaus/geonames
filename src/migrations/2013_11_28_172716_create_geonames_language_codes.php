<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesLanguageCodes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geonames_language_codes', function(Blueprint $table)
		{
			$table->string('iso_639_3', 4)->index();
			$table->string('iso_639_2', 50)->index();
			$table->string('iso_639_1', 50)->index();
			$table->string('language_name', 200);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geonames_language_codes');
	}

}
