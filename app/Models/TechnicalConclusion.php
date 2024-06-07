<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalConclusion extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'technical_conclusions';

    protected $fillable = [
        'code_1C', 'number',
        'client_name', 'client_phone', 
        'product_name', 'product_article','factory_number', 'barcode',
        'service_partner', 'service_contract', 'point_of_sale', 'autor', 
        'parent_doc', 'defect_code', 'symptom_code', 'resolution', 
        'date', 'date_of_sale', 'date_of_claim',
        'details', 'type_of_claim',
    ];
}
