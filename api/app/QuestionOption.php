<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends APIModel
{
    protected $table = 'question_options';
    protected $fillable = ['question_id', 'description', 'order'];
}
