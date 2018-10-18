<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends APIModel
{
    protected $table = 'ratings';
    protected $fillable = ['payload', 'payload_value', 'account_id', 'value'];
}
