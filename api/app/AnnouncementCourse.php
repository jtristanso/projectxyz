<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnnouncementCourse extends APIModel
{
    protected $table = 'announcement_courses';
    protected $fillable = ['announcement_id', 'course_id'];
}
