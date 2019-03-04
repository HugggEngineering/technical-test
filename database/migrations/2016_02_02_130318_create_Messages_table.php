<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration {

	public function up()
	{
		Schema::create('messages', function(Blueprint $table) {
			$table->string('id');
			$table->timestamps();
			$table->string('hug_id')->default('');
			$table->string('message_type');
            $table->text('message')->default('');
		});
	}

	public function down()
	{
		Schema::drop('messages');
	}
}
