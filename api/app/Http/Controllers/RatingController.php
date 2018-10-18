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

    public function retrieve(Request $request){
      $data = $request->all();
      $this->retrieveDB($data);
      $result = $this->response['data'];

      if(sizeof($result) > 0){
        $payload = $data['condition'][0]['value'];
        $payloadValue = $data['condition'][1]['value'];
        $avg = Rating::where('payload', '=', $payload)->where('payload_value', '=', $payloadValue)->avg('value');
        $total = Rating::where('payload', '=', $payload)->where('payload_value', '=', $payloadValue)->sum('value');
        $stars = round($avg);
        $this->response['total'] = $total;
        $this->response['avg'] = $avg;
        $this->response['stars'] = $stars;
      }
      return $this->response();
    }
}
