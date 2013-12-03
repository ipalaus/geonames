<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesCountries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geonames_countries', function(Blueprint $table)
		{
			$table->string('iso_alpha2', 2);
			$table->string('iso_alpha3', 3)->index();
			$table->integer('iso_numeric')->index();
			$table->string('fips_code', 3);
			$table->string('name', 200);
			$table->string('capital', 200);
			$table->double('area');
			$table->integer('population');
			$table->string('continent_id', 2)->index();
			$table->string('tld', 3);
			$table->string('currency', 3);
			$table->string('currency_name', 20);
			$table->string('phone', 10);
			$table->string('postal_code_format', 100);
			$table->string('postal_code_regex', 255);
			$table->integer('name_id')->index();
			$table->string('languages', 200);
			$table->string('neighbours', 100);
			$table->string('equivalent_fips_code', 10);

			$table->primary('iso_alpha2');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geonames_countries');
	}

}
