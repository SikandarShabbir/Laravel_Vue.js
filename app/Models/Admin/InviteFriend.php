<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Admin\Locations;
use App\Models\Admin\Payments;
use App\Models\Admin\Refund;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\BookedTargets;
use App\Helpers\PhoneNumberFormat;
use Carbon\Carbon;

class InviteFriend extends Model
{
    use SoftDeletes;
    protected $table = 'invite_friends';
    protected $primaryKey = 'invite_friend_id';
    const CREATED_AT = 'invitefriend_created_at';
	const UPDATED_AT = 'invitefriend_updated_at';
    protected $dates = ['deleted_at']; 


}
