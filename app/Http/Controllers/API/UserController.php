<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Carbon\Carbon;
use GuzzleHttp\Client;
use DB;
use Lcobucci\JWT\Parser;
use App\Helpers\CheckSite;
class UserController extends Controller{
    public $successStatus = 200;
    public $unAuthorizedStatus = 401;
    public $unprocessableStatus = 422;

    public function login(Request $request){
        // dd("Hello");
        // dd(bcrypt($request->password));

        $request->validate([
            'username' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->username)->first();
        $site_id = CheckSite::CheckUserSite($user->site_id);
        // dd($site_id);
       // print_r($site_id);exit();

        if ($user && $site_id== 1) {
            // dd(bcrypt($request->password));
            if (\Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['access_token' => $token];
                // dd($token);
                //return response($response, 200);
                return response()->json(['error' => false ,'access_token'=>$token], $this->successStatus); 
            } else {
                $response = "Password missmatch";
                return response()->json(['error'=> true, 'message'=>'Invalid password'], 422); 
            }
        } else {
            if($site_id== 0){
                $response = 'Invalid site url';
                return response()->json(['error'=> true, 'message'=>'Invalid site url'], 422); 
            }else{
                $response = 'User does not exist';
                return response()->json(['error'=> true, 'message'=>'User does not exist'], 422); 
            }
            
        }
    }
    public function logout(Request $request){

     $loggedOUt=    $request->user()->token()->delete(); 
        if($loggedOUt ){
            return response()->json(['error'=>false, 'message' => 'success'], $this->successStatus);
        }else{
            return response()->json(['error'=>true, 'message' => 'success'], $this->successStatus);
        } 
    }
    public function isloggedin(Request $request){
        // dd($request->user()->token());
        if($user = $request->user()->id){
            $token=  $request->user()->token();
            if($token){
                return response()->json(['error'=>false, 'message' => 'success'], $this->successStatus);
            }else{
                return response()->json(['error'=>true, 'message' => 'Unauthorized'], $this->unAuthorizedStatus);
            }
        }else{
            return response()->json(['error'=>true, 'message' => 'Unauthorized'], $this->unAuthorizedStatus);
        }   
    } 
}