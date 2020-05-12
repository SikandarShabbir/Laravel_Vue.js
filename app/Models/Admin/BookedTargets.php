<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BookedTargets extends Model
{
    //
    use SoftDeletes;
    protected $table = 'booked_targets';
    protected $primaryKey = 'bookedtarget_id';
    const CREATED_AT = 'bookedtarget_created_at';
	const UPDATED_AT = 'bookedtarget_updated_at';
}
