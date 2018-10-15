<?php

namespace App\Http\Controllers;

use App\AccountProfile;
use Illuminate\Http\Request;
use Carbon\Carbon;
class AccountProfileController extends ClassWorxController
{
    function __construct(){
        $this->model = new AccountProfile();
    }
    public function create(Request $request){
    	$data = $request->all();
    	if(isset($data['profile_url'])){
    		$date = Carbon::now()->toDateString();
    		$time = str_replace(':', '_',Carbon::now()->toTimeString());
    		$ext = $request->file('file')->extension();
    		$filename = $data['account_id'].'_'.$date.'_'.$time.'.'.$ext;
    		$result = $request->file('file')->storeAs('profiles', $filename);
    		$this->model = new AccountProfile();
    		$insertData = array(
    			'account_id'	=> $data['account_id'],
    			'profile_url'	=> '/storage/profiles/'.$filename
    		);
    		$this->insertDB($insertData);
    		return $this->response();
    	}else{
    		return response()->json(array('data' => null));
    	}
    }
}
