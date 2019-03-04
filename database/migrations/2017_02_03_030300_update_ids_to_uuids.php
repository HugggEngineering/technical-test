<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIdsToUuids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hugs', function (Blueprint $table) {
            //
            $table->string('id', 50)->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('id', 50)->change();
        });
        Schema::table('prices', function (Blueprint $table) {
            //
            $table->string('id', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
