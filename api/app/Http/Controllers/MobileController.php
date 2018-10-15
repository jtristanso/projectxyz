<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class MobileController extends ClassWorxController
{
    function __construct(){
      $this->model = new Account();
    }

    public function auth(Request $request){
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
      if(sizeof($result) > 0){
        if(Hash::check($data['password'], $result[0]['password'])){
          return response()->json(array(
            "data" => $result,
            "error" => "", 
            "timestamps" => Carbon::now()
          ));
        }
      }
      return response()->json(array(
        "data" => [],
        "error" => "Username and Password did not matched!",
        "timestamps" => Carbon::now()
      ));
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
