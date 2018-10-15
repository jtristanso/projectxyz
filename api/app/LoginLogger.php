<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginLogger extends APIModel
{
    protected $table = 'login_loggers';
    protected $fillable = ['account_id', 'browser', 'client_ip', 'address', 'remote_address', 'location'];
}
