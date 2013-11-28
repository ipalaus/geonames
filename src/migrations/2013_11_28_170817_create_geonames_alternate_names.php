<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesAlternateNames extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geonames_alternate_names', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('name_id')->index();
			$table->string('iso_language', 7);
			$table->string('alternate_name', 200);
			$table->boolean('is_preferred')->index();
			$table->boolean('is_short')->index();
			$table->boolean('is_colloquial')->index();
			$table->boolean('is_historic');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geonames_alternate_names');
	}

}
