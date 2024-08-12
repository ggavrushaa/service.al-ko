<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaimServiceWork extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'warranty_claim_service_works';

    protected $hidden = [
        'created_at', 'updated_at', 'pivot',
    ];

    protected $fillable = [
        'warranty_claim_id',
        'service_work_id',
        'qty',
        'price',
        'sum',
        'discount',
        'line_number',
    ];

    public function serviceWork()
    {
        return $this->belongsTo(ServiceWorks::class, 'service_work_id');
    }
}
