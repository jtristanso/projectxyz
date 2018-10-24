<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends APIModel
{
    protected $table = 'product_prices';
    protected $fillable = ['product_id', 'price'];
}
