<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnrolledAccount extends APIModel
{
    protected $table = 'enrolled_accounts';
    protected $fillable = ['course_id', 'account_id', 'status', 'declined_date', 'confirmed_date'];
}
