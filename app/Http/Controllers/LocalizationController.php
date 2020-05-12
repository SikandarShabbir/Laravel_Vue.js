<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lang;
use App;
use Session;

class LocalizationController extends Controller
{
    public function index(Request $request,$local){
        App::setlocale($local);
        $lang = Lang::get('lang');
        return response()->json(['error'=>false,  'language'=> $lang]); 
    }
}
