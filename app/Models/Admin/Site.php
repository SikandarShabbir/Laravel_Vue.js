<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class Site extends Model
{
    use SoftDeletes;
    protected $table = 'sites';
    protected $primaryKey = 'site_id';
    const CREATED_AT = 'site_created_at';
	const UPDATED_AT = 'site_updated_at';
    protected $dates = ['deleted_at']; 


}
