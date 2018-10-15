<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountWorkExperience extends APIModel
{
    protected $table = 'account_work_experiences';
    protected $fillable = ['account_id', 'position', 'company', 'company_address', 'year_started', 'month_started', 'year_ended', 'month_ended'];
}
