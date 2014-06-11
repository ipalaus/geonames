<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeonamesUtf8mb4 extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // requires MySQL >= 5.5
        DB::statement('ALTER TABLE geonames_alternate_names CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE geonames_alternate_names CHANGE alternate_name alternate_name VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        DB::statement('ALTER TABLE geonames_names CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        DB::statement('ALTER TABLE geonames_names CHANGE name name VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE geonames_alternate_names CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE geonames_alternate_names CHANGE alternate_name alternate_name VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci');

        DB::statement('ALTER TABLE geonames_names CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        DB::statement('ALTER TABLE geonames_names CHANGE name name VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }
}
