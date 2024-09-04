<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admin extends Model {

	protected $table = 'admin';
	public $timestamps = true;

	public function getAll()
	{
		$admins = DB::table('admin')
			->select('*')
			->get();
		return $admins;
	}

}