<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends APIModel
{
    protected $table = "schools";
    protected $fillable = ['name',  'address', 'code'];
}
