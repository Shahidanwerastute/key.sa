<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model {

	protected $table = 'admin_role';
	public $timestamps = false;

	protected $fillable = ['role_id','uid'];

	
}