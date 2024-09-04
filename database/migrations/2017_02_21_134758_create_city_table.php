<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCityTable extends Migration {

	public function up()
	{
		Schema::create('city', function(Blueprint $table) {
			$table->increments('id');
			$table->string('eng_title', 191);
			$table->string('arb_title', 191);
			$table->integer('region_id')->unsigned()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('city');
	}
}