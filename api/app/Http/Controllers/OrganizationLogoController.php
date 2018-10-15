<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrganizationLogo;
use Carbon\Carbon;
class OrganizationLogoController extends ClassWorxController
{
    function __construct(){
      $this->model = new OrganizationLogo();
    }

    public function create(Request $request){
	  	$data = $request->all();
	  	if(isset($data['logo_url'])){
	  		$date = Carbon::now()->toDateString();
	  		$time = str_replace(':', '_',Carbon::now()->toTimeString());
	  		$ext = $request->file('file')->extension();
	  		$filename = $data['organization_id'].'_'.$date.'_'.$time.'.'.$ext;
	  		$result = $request->file('file')->storeAs('logos', $filename);
	  		$this->model = new OrganizationLogo();
	  		$insertData = array(
	  			'organization_id'	=> $data['organization_id'],
	  			'url'	=> '/storage/logo/'.$filename
	  		);
	  		$this->insertDB($insertData);
	  		return $this->response();
	  	}else{
	  		return response()->json(array('data' => null));
	  	}
  }
}
