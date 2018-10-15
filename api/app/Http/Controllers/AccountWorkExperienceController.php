<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountWorkExperience;

class AccountWorkExperienceController extends ClassWorxController
{
  function __construct(){
    $this->model = new AccountWorkExperience();
    $this->notRequired = array(
      'year_ended',
      'month_ended'
    );
  }    
}
