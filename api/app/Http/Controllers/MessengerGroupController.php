<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MessengerGroup;
class MessengerGroupController extends ClassWorxController
{
    function __construct(){
      $this->model = new MessengerGroup();
    }


    public function retrieve(Request $request){
      $data = $request->all();
      $this->retrieveDB($data);

      $result = $this->response['data'];

      if(sizeof($result) > 0){
        $i = 0;
        foreach ($result as $key) {
          $result[$i]['account_details'] = $this->retrieveAccountDetails($result[$i]['account_id']);
          $i++;
        }
        $this->response['data'] = $result;
      }
      return $this->response();
    }
}
