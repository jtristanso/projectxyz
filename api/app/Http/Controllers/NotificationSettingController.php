<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NotificationSetting;
class NotificationSettingController extends ClassWorxController
{
    function __construct(){
    	$this->model = new NotificationSetting();
    }
}
