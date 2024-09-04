<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminUserTable extends Migration {

	public function up()
	{
		Schema::create('admin_user', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('admin_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('admin_user');
	}
}