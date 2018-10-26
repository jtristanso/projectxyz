<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends APIModel
{
    protected $table = 'product_photos';
    protected $fillable = ['product_id', 'status', 'url'];
}
