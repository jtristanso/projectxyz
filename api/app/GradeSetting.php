<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradeSetting extends APIModel
{
    protected $table = 'grade_settings';
    protected $fillable = ['semester_id', 'course_id', 'quizzes_rate', 'exams_rate', 'attendance_rate', 'projects_rate', 'passing_percentage_quizzes', 'passing_percentage_exams'];
}
