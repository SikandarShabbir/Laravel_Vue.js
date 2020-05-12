<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PhoneNumberFormat;
use App\Models\Admin\BookedTargets;
use App\Models\Admin\Locations;
use DB;
use App\Helpers\CheckSite;

class PartialPayments extends Model
{
    use SoftDeletes;
    protected $table = 'partial_payment';
    protected $primaryKey = 'partial_payment_id';
    const CREATED_AT = 'partial_payment_created_at';
	const UPDATED_AT = 'partial_payment_updated_at';
    protected $dates = ['partial_payment_deleted_at']; 

    protected $guarded = [];

}
