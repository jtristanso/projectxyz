<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSold extends APIModel
{
    protected $table = 'product_solds';
    protected $fillable = ['product_id', 'product_buyer_id'];
}
