<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleCredential extends APIModel
{
    protected $table = 'google_credentials';
    protected $fillable = ['id', 'account_id', 'token'];
}
