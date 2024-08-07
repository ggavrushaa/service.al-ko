<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'product_groups';

    protected $fillable = [
        'code_1C',
        'name',
    ];

    public function works()
    {
        return $this->hasMany(ServiceWorks::class, 'product_group_id', 'code_1C');
    }
}
