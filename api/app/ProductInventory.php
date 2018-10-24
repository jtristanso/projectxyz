<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductInventory extends APIModel
{
    protected $table = 'product_inventories';
    protected $fillable = ['product_id', 'qty'];
}
