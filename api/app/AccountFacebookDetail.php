<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountFacebookDetail extends Model
{
    protected $table = 'account_facebook_details';
    protected $fillable = ['account_id', 'facebook', 'facebook_name'];
}
