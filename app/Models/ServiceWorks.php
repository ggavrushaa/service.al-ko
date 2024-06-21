<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceWorks extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'service_works';

    public function group()
    {
        return $this->belongsTo(ProductGroup::class, 'product_group_id');
    }
}
