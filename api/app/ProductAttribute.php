<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends APIModel
{
    protected $table = 'product_attributes';
    protected $fillable = ['product_id','attribute','value'];
}
