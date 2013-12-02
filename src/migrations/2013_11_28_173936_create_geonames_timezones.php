<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesTimezones extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geonames_timezones', function(Blueprint $table)
		{
			$table->string('id', 200)->index();
			$table->decimal('gmt_offset', 3, 1);
			$table->decimal('dst_offset', 3, 1);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geonames_timezones');
	}

}
