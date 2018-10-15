<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Course extends APIModel
{
    protected $table = 'courses';
    protected $fillable = ['semester_id', 'account_id', 'code', 'description', 'units', 'time_start', 'time_end', 'days'];
}
