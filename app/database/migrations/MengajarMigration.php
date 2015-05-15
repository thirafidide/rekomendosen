<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class MengajarMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('mengajar');
        Capsule::schema()->create('mengajar', function($table) {
            $table->string('matakuliah_id');
            $table->string('dosen_id');
            $table->timestamps();
        });
    }
}
