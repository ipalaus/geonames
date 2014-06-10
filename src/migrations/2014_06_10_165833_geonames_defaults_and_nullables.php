<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeonamesDefaultsAndNullables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE geonames_names MODIFY COLUMN elevation INT DEFAULT NULL');
        DB::statement('ALTER TABLE geonames_countries MODIFY COLUMN area DOUBLE DEFAULT NULL');
        DB::statement('ALTER TABLE geonames_countries MODIFY COLUMN phone VARCHAR(32)');
        DB::statement('ALTER TABLE geonames_countries MODIFY COLUMN name_id INT DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE geonames_names MODIFY COLUMN elevation INT NOT NULL');
        DB::statement('ALTER TABLE geonames_countries MODIFY COLUMN area DOUBLE NOT NULL');
        DB::statement('ALTER TABLE geonames_countries MODIFY COLUMN phone VARCHAR(10)');
        DB::statement('ALTER TABLE geonames_countries MODIFY COLUMN name_id INT NOT NULL');
    }

}
