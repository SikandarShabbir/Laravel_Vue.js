<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    // use SoftDeletes;
    protected $primaryKey = 'bookedtarget_id';
    // protected $table = 'bookings';
    const CREATED_AT = 'day_created_at';
	const UPDATED_AT = 'day_updated_at';                    
    protected $dates = ['deleted_at']; 
    protected $table = 'booked_targets';

}
