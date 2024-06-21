<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'products';

    public function productPrices()
    {
        return $this->hasOne(ProductPrices::class, 'product_id');
    }

}
