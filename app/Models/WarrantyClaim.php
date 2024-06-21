<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    use HasFactory;
    
    protected $connection = 'second_db';
    protected $table = 'warranty_claims';

    protected $fillable = [
        'code_1C', 'number',
        'client_name', 'client_phone', 
        'product_name', 'product_article','factory_number', 'barcode',
        'service_partner', 'service_contract', 'point_of_sale', 'autor',
        'date', 'date_of_sale', 'date_of_claim',
        'details', 'type_of_claim',
        'is_deleted', 'created', 'edited',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'autor');
    }

    public function spareParts()
    {
        return $this->hasMany(WarrantyClaimSpareParts::class, 'warranty_claim_id', 'id');
    }
    
}
