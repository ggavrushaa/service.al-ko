<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaimSpareParts extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'warranty_claim_spareparts';

    protected $fillable = [
        'warranty_claim_id',
        'line_number',
        'spare_parts',
        'qty',
        'price', 
        'discount',
        'sum',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'pivot', 'warranty_claim_id', 'id', 'sum',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'spare_parts', 'articul', 'mysql');
    }

    public function warrantyClaim()
    {
        return $this->belongsTo(WarrantyClaim::class, 'warranty_claim_id');
    }
}
