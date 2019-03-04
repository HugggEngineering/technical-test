<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTagIdsToOffer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function(Blueprint $table) {
            $table->string('id');
            $table->string('tag_name');
            $table->integer('type');
            $table->timestamps();
        });
        Schema::table('prices', function(Blueprint $table) {
            $table->string('tag_group_id')->default('');
        });
        Schema::table('prices', function(Blueprint $table) {
            $table->string('tag_id')->default('');
        });
        Schema::create('price_tag', function(Blueprint $table) {
            $table->string('tag_id')->default('');
            $table->string('price_id')->default('');
            $table->increments('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prices', function(Blueprint $table) {
            $table->dropColumn('tag_group_id');
        });
        Schema::table('prices', function(Blueprint $table) {
            $table->dropColumn('tag_id');
        });
        Schema::drop('tags', function(Blueprint $table) {

        });
        Schema::drop('price_tag', function(Blueprint $table) {

        });
    }
}
