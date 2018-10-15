<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\DiscussionReport;
class DiscussionReportController extends ClassWorxController
{
    function __construct(){
      $this->model = new DiscussionReport();
    }

    public function retrieve(Request $request){
    	$data = $request->all();
    	$this->retrieveDB($data);
    	if(sizeof($this->response['data']) > 0){
    		$i = 0;
    		$result = $this->response['data'];
    		foreach ($result as $key) {
    			$this->response['data'][$i]['account'] = $this->getAccount($result[$i]['account_id']);
    			$i++;
    		}
    	}
    	return $this->response();
    }

    public function getAccount($accountId){
    	$result = Account::where('id', '=', $accountId)->get();
    	return (sizeof($result) > 0) ? $result[0] : null;
    }
}
