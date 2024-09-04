<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarCategoryTable extends Migration {

	public function up()
	{
		Schema::create('car_category', function(Blueprint $table) {
			$table->increments('id');
			$table->string('eng_title', 191);
			$table->string('arb_title', 191);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('car_category');
	}
}