<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School;
class SchoolController extends ClassWorxController
{
    function __construct(){
    	$this->model = new School();
    }

    public function create(Request $request){
      $data = $request->all();
      $data["code"] = $this->generateCode();
      $this->model = new School();
      $this->insertDB($data);
      return $this->response();
    }

    public function generateCode(){
      $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 64);
      $codeExist = School::where('code', '=', $code)->get();
      if(sizeof($codeExist) > 0){
        $this->generateCode();
      }else{
        return $code;
      }
    }
}
