<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBranchTable extends Migration {

	public function up()
	{
		Schema::create('branch', function(Blueprint $table) {
			$table->increments('id');
			$table->string('eng_title', 191);
			$table->string('arb_title', 191);
			$table->integer('city_id')->unsigned()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('branch');
	}
}