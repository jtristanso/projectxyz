<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlannerOther extends APIModel
{
    protected $table = 'planner_others';
    protected $fillable = ['account_id', 'title'];
}
