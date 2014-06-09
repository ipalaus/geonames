<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeonamesAdminDivisionsLongerCode extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE geonames_admin_divisions MODIFY COLUMN code VARCHAR(32) NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE geonames_admin_divisions MODIFY COLUMN code VARCHAR(6) NOT NULL');
    }

}
