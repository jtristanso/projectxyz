<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountProfile extends APIModel
{
		protected $table = 'account_profiles';
		protected $fillable = ['account_id', 'profile_url'];
}
