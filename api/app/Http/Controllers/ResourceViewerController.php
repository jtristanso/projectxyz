<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResourceViewer;
use App\Account;
use App\AccountInformation;
use App\AccountProfile;
use App\AccountDegree;
class ResourceViewerController extends ClassWorxController
{
    function __construct(){
      $this->model = new ResourceViewer();
    }

    public function retrieve(Request $request){
    	$data = $request->all();
      $this->model = new ResourceViewer();
      $result = $this->retrieveDB($data);
      if(sizeof($result) > 0){
      	$i = 0;
        foreach ($result as $key) {
          $accountResult = Account::where('id', '=', $result[$i]['account_id'])->get();
          $accountInfoResult = AccountInformation::where('account_id', '=', $result[$i]['account_id'])->get();
          $accountProfileResult = AccountProfile::where('account_id', '=', $result[$i]['account_id'])->get();
          $accountDegreeResult = AccountDegree::where('account_id', '=', $result[$i]['account_id'])->get();
          $this->response['data'][$i]['account'] = (sizeof($accountResult) > 0) ? $accountResult[0] : null;
          $this->response['data'][$i]['account_information'] = (sizeof($accountInfoResult) > 0) ? $accountInfoResult[0] : null;
          $this->response['data'][$i]['account_profile'] = (sizeof($accountProfileResult) > 0) ? $accountProfileResult[0] : null;
          $this->response['data'][$i]['account_degree'] = (sizeof($accountDegreeResult) > 0) ? $accountDegreeResult[0] : null;
          $i++;
        }
      }
      else{
        //
      }
      return $this->response();
    }
}
