<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MetaPage extends Model
{

    protected $table = 'meta_pages';

    protected $fillable = [
        'eng_meta_title',
        'arb_meta_title',
        'eng_meta_description',
        'arb_meta_description',
        'eng_meta_keyword',
        'arb_meta_keyword',
        'page'
    ];



}