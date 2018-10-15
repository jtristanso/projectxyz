<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventFeaturedImage extends APIModel
{
    protected $table = 'event_featured_images';
    protected $fillable = ['event_id', 'url'];
}
