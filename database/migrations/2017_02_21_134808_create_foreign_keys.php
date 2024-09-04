<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('car_group', function(Blueprint $table) {
			$table->foreign('category_id')->references('id')->on('car_category')
						->onDelete('set null')
						->onUpdate('no action');
		});
		Schema::table('car_type', function(Blueprint $table) {
			$table->foreign('group_id')->references('id')->on('car_group')
						->onDelete('set null')
						->onUpdate('no action');
		});
		Schema::table('car_model', function(Blueprint $table) {
			$table->foreign('car_type_id')->references('id')->on('car_type')
						->onDelete('set null')
						->onUpdate('no action');
		});
		Schema::table('city', function(Blueprint $table) {
			$table->foreign('region_id')->references('id')->on('region')
						->onDelete('set null')
						->onUpdate('no action');
		});
		Schema::table('branch', function(Blueprint $table) {
			$table->foreign('city_id')->references('id')->on('city')
						->onDelete('set null')
						->onUpdate('no action');
		});
		Schema::table('admin_user', function(Blueprint $table) {
			$table->foreign('admin_id')->references('id')->on('admin')
						->onDelete('set null')
						->onUpdate('no action');
		});
		Schema::table('admin_user', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('set null')
						->onUpdate('no action');
		});
		Schema::table('customer_user', function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customer')
						->onDelete('set null')
						->onUpdate('no action');
		});
		Schema::table('customer_user', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('set null')
						->onUpdate('no action');
		});
	}

	public function down()
	{
		Schema::table('car_group', function(Blueprint $table) {
			$table->dropForeign('car_group_category_id_foreign');
		});
		Schema::table('car_type', function(Blueprint $table) {
			$table->dropForeign('car_type_group_id_foreign');
		});
		Schema::table('car_model', function(Blueprint $table) {
			$table->dropForeign('car_model_car_type_id_foreign');
		});
		Schema::table('city', function(Blueprint $table) {
			$table->dropForeign('city_region_id_foreign');
		});
		Schema::table('branch', function(Blueprint $table) {
			$table->dropForeign('branch_city_id_foreign');
		});
		Schema::table('admin_user', function(Blueprint $table) {
			$table->dropForeign('admin_user_admin_id_foreign');
		});
		Schema::table('admin_user', function(Blueprint $table) {
			$table->dropForeign('admin_user_user_id_foreign');
		});
		Schema::table('customer_user', function(Blueprint $table) {
			$table->dropForeign('customer_user_customer_id_foreign');
		});
		Schema::table('customer_user', function(Blueprint $table) {
			$table->dropForeign('customer_user_user_id_foreign');
		});
	}
}