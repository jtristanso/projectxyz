<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Illuminate\Support\Facades\Validator;

use App\UserAuth;
use App\Account;
use App\NotificationSetting;
use App\LoginLogger;
use App\Jobs\Email;

class AuthenticateController extends Controller
{
  public function __construct()
   {
     // Apply the jwt.auth middleware to all methods in this controller
     // except for the authenticate method. We don't want to prevent
     // the user from retrieving their token if they don't already have it
     $this->middleware('jwt.auth', ['except' => ['authenticate']]);
   }
  public function index()
  {
    $users = UserAuth::all();
    return $users;
  }
  public function refreshToken(){
    // config/jwt.php ttl to change token life
    // if ($token = JWTAuth::parseToken()->refresh()){
    //   return response()->json(['error' => 'failed_parse', 'token' => JWTAuth::getToken()], 401);
    // }
    $current_token  = JWTAuth::getToken();
    $token          = JWTAuth::refresh($current_token);
    return response()->json(compact('token'));
  }
  public function authenticate(Request $request)
  {
      
    $data = $request->all();
    $text = array('email' => $data['username']);

    $credentials = null;
    $result = null;
    if($this->customValidate($text) == true){        
      $credentials = array("email" => $data['username'], 'password' => $data['password']);
      $result = Account::where('email', '=', $data['username'])->get();
    }else{
      $credentials = array("username" => $data['username'], 'password' => $data['password']);
      $result = Account::where('username', '=', $data['username'])->get();
    }
    try {
      // verify the credentials and create a token for the user
      if (! $token = JWTAuth::attempt($credentials)) {
          return response()->json(['error' => 'invalid_credentials'], 401);
      }
    } catch (JWTException $e) {
      // something went wrong
      return response()->json(['error' => 'could_not_create_token'], 500);
    }
    // if no errors are encountered we can return a JWT
    if(sizeof($result) > 0){
      $notifResult = NotificationSetting::where('account_id', '=', $result[0]['id'])->get();
      if(sizeof($notifResult) > 0){
        if($notifResult[0]['email'] === "ON"){
          // Notify via email
          dispatch(new Email($result[0], 'login'));
        }else if($notifResult[0]['sms'] === "ON"){
          // Notify via SMS
        }else if($notifResult[0]['fb_messenger'] === "ON"){
          // Notify via FB Messenger
        }
      }
        
    }else{
      //
    }
    return response()->json(compact('token'));
  }
  public function deauthenticate(){
    JWTAuth::invalidate(JWTAuth::getToken());
    return response()->json(['token' => NULL]);
  }
  public function getAuthenticatedUser()
  {
      try {
        if (! $user = JWTAuth::parseToken()->authenticate()) {
          return response()->json(['user_not_found'], 404);
        }
      } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        return response()->json(['token_expired'], $e->getStatusCode());
      } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return response()->json(['token_invalid'], $e->getStatusCode());
      } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['token_absent'], $e->getStatusCode());
      }

      // the token is valid and we have found the user via the sub claim
      return response()->json($user);
  }
  public function customValidate($text){
    $validation = array('email' => 'required|email'); 
    return $this->validateReply($text, $validation);
  }

  public function validateReply($text, $validation){
    $validator = Validator::make($text, $validation);
    if($validator->fails()){
      return false;
    }
    else
      return true;
  }
}
