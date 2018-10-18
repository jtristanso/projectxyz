<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
class RatingController extends ClassWorxController
{
    function __construct(){
      $this->model = new Rating();
    }

    public function create(Request $request){
      $data = $request->all();

      $result = Rating::where('account_id', '=', $data['account_id'])
      ->where('payload', '=', $data['payload'])->where('payload_value', '=', $data['payload_value'])->get();
      if(sizeof($result) > 0){
        $this->model = new Rating();
        $this->response['error']['message'] = "You've submitted reviews already.";
        $this->response['error']['status'] = 100;
      }else{
        $this->insertDB($data);
      }
      return $this->response();
    }
}
