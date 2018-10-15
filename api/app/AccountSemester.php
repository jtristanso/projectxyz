<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountSemester extends APIModel
{
	  protected $table = 'account_semesters';
    protected $fillable = ['semester_id', 'account_id', 'code', 'grade_setting'];
}
