<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricesTable extends Migration {

	public function up()
	{
		Schema::create('prices', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('list_price');
			$table->integer('sale_price');
			$table->string('brand_id');
			$table->string('description');
			$table->string('label');
            $table->string('subtitle')->default('');
            $table->string('open_graph_image')->default('default.jpg');
		});
	}

	public function down()
	{
		Schema::drop('prices');
	}
}
