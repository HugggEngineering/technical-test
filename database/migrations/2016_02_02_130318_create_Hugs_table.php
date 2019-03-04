<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHugsTable extends Migration {

	public function up()
	{
		Schema::create('hugs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('sender_id')->unsigned();
			$table->integer('receiver_id')->unsigned();
            $table->string('shortcode')->default('');
            $table->string('type')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('hugs');
	}
}
