<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\AccountInformation;
class AccountInformationController extends ClassWorxController
{
    function __construct(){  
        $this->model = new AccountInformation();
        $this->notRequired = array(
          'sex', 'birth_date', 'cellular_number', 'address'
        );
    }

    public function update(Request $request){
      $data = $request->all();
      if($this->checkIfExist($data['account_id']) == true){
        $this->model = new AccountInformation();
        $this->updateDB($data);
        return $this->response();
      }else{
        $this->model = new AccountInformation();
        $this->insertDB($data);
        return $this->response();
      }
    }

    public function checkIfExist($accountId){
      $result = AccountInformation::where('account_id', '=', $accountId)->get();
      if(sizeof($result) > 0){
        return true;
      }else{
        return false;
      }
    }
}
