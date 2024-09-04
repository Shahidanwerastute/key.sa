<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LoyaltyProgram extends Model
{

    protected $table = 'setting_loyalty_programs';
    public $timestamps = false;

    public function getAll()
    {
        $records = DB::table($this->table)->get();
        return $records;
    }

    public function getSingle($id)
    {
        $records = DB::table($this->table)->where('id', $id)->first();
        return $records;
    }

    public function saveData($data)
    {
        $id = DB::table($this->table)->insertGetId($data);
        return $id;
    }

    public function updateData($data, $id)
    {
        $updated = DB::table($this->table)
            ->where('id', $id)
            ->update($data);
        if ($updated) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteData($id)
    {
        DB::table($this->table)->where('id', $id)->delete();
    }
}