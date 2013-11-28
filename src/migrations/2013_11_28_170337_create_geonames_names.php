<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesNames extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geonames_names', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 200);
			$table->string('ascii_name', 200);
			$table->string('alternate_names', 4000);
			$table->decimal('latitude', 10, 7);
			$table->decimal('longitude', 10, 7);
			$table->string('f_class', 1);
			$table->string('f_code', 10);
			$table->string('country_id', 2)->index();
			$table->string('cc2', 60);
			$table->string('admin1', 20)->index();
			$table->string('admin2', 80)->index();
			$table->string('admin3', 20)->index();
			$table->string('admin4', 20)->index();
			$table->integer('population')->index();
			$table->integer('elevation');
			$table->integer('gtopo30');
			$table->string('timezone_id', 40)->index();
			$table->date('modification_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geonames_names');
	}

}
