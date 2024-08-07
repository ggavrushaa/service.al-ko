<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceWorks extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'service_works';

    protected $fillable = [
        'code_1C',
        'name',
        'product_group_id',
        'duration_decimal',
        'duration_minutes',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'pivot', 'sum',
    ];

    public function group()
    {
        return $this->hasOne(ProductGroup::class, 'code_1C', 'product_group_id');
    }
}
