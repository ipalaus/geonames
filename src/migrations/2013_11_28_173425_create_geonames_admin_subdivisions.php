<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeonamesAdminSubdivisions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geonames_admin_subdivisions', function(Blueprint $table)
		{
			$table->string('code', 15);
			$table->text('name');
			$table->text('name_ascii');
			$table->integer('name_id')->index();
			$table->timestamps();

			$table->primary('code');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geonames_admin_subdivisions');
	}

}
