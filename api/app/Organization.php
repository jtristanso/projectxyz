<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends APIModel
{
    protected $table = 'organizations';
    protected $fillable = ['account_id', 'school_id', 'name', 'type', 'vision', 'mission', 'about', 'status', 'address'];
}
