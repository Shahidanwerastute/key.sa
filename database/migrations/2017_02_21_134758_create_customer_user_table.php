<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerUserTable extends Migration {

	public function up()
	{
		Schema::create('customer_user', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('customer_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('customer_user');
	}
}