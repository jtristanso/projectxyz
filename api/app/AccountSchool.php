<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountSchool extends APIModel
{
    protected $table = 'account_schools';
		protected $fillable = ['account_id', 'school_id'];
}
