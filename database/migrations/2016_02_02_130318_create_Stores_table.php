<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStoresTable extends Migration {

	public function up()
	{
		Schema::create('stores', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('price_id');
			$table->string('lat');
			$table->string('lon');
            $table->string('brand_id');
		});
	}

	public function down()
	{
		Schema::drop('stores');
	}
}
