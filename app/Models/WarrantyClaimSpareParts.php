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
        'price_without_vat',
        'amount_without_vat',
        'amount_vat',
        'amount_with_vat',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'spare_parts', 'articul', 'mysql');
    }
}
