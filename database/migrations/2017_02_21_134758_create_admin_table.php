<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminTable extends Migration {

	public function up()
	{
		Schema::create('admin', function(Blueprint $table) {
			$table->increments('id');
			$table->string('first_name', 191);
			$table->string('last_name');
			$table->string('mobile', 191);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('admin');
	}
}