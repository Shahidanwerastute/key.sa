<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCarModelTable extends Migration {

	public function up()
	{
		Schema::create('car_model', function(Blueprint $table) {
			$table->increments('id');
			$table->string('eng_title', 191);
			$table->string('arb_title', 191);
			$table->integer('year');
			$table->enum('transmission', array('A', 'M'));
			$table->integer('no_of_bags');
			$table->integer('no_of_passengers');
			$table->integer('no_of_doors');
			$table->text('description');
			$table->integer('car_type_id')->unsigned()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('car_model');
	}
}