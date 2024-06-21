<?php

namespace App\Models\TechnicalConclusion;

use App\Enums\TechnicalConclusionStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TechnicalConclusion extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'technical_conclusions';

    protected $fillable = [ 
    'code_1C', 'number',
    'client_name', 'client_phone', 
    'product_name', 'product_article', 'product_group_id','factory_number', 'barcode',
    'service_partner', 'service_contract', 'point_of_sale', 'autor', 
    'parent_doc', 'defect_code', 'symptom_code', 'resolution', 
    'date', 'date_of_sale', 'date_of_claim',
    'details', 'type_of_claim', 'file_paths',
    'sender_name', 'sender_phone', 'receipt_number', 'deteails_reason',
    'comment', 'comment_part', 'comment_service', 'status',
    ];

    protected $casts = [
        'status' => TechnicalConclusionStatusEnum::class,
    ];

    public function files()
    {
        return $this->hasMany(TechnicalConclusionFile::class, 'technical_conclusion_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'autor');
    }
}
