<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBuyer extends APIModel
{
    protected $table = 'product_buyers';
    protected $fillable = ['product_id', 'account_id', 'qty'];
}
