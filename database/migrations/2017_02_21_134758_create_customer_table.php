<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomerTable extends Migration {

	public function up()
	{
		Schema::create('customer', function(Blueprint $table) {
			$table->increments('id');
			$table->string('first_name', 191);
			$table->string('last_name', 191);
			$table->string('mobile_no', 191);
			$table->string('email', 191);
			$table->string('id_type', 191);
			$table->string('id_no', 191);
			$table->string('nationality', 191);
			$table->date('dob');
			$table->date('id_expiry_date');
			$table->string('license_no', 191);
			$table->date('license_expiry_date');
			$table->string('id_image', 191);
			$table->string('license_image', 191);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('customer');
	}
}