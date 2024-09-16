<?php

namespace App\Models;

use App\Enums\WarrantyClaimStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TechnicalConclusion\TechnicalConclusion;
use Illuminate\Database\Capsule\Manager;

class WarrantyClaim extends Model
{
    use HasFactory;
    
    protected $connection = 'second_db';
    protected $table = 'warranty_claims';

    protected $fillable = [
        'code_1C', 
        'number', 'client_name', 'client_phone', 
        'product_name', 'product_article','factory_number', 'barcode',
        'service_partner', 'service_contract', 'point_of_sale', 'autor',
        'date', 'date_of_sale', 'date_of_claim', 'type_of_claim',
        'is_deleted',
        'product_group_id', 'file_paths', 
        'comment', 'comment_service', 'comment_part', 
        'sender_name', 'sender_phone', 'receipt_number', 
        'details', 'deteails_reason', 'status', 'manager_id', 'number_1c', 'status_1c',
        'spare_parts_sum', 'service_works_sum', 
    ];

    protected $casts = [
        'status' => WarrantyClaimStatusEnum::class,
    ];

    public function technicalConclusion()
    {
        return $this->hasOne(TechnicalConclusion::class, 'warranty_claim_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'service_contract');
    }


    public function autor()
    {
        return $this->belongsTo(UserPartner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'autor');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function spareParts()
    {
        return $this->hasMany(WarrantyClaimSpareParts::class, 'warranty_claim_id', 'id');
    }

    public function files()
    {
        return $this->hasMany(WarrantyClaimFile::class, 'warranty_claim_id');
    }
    
    public function serviceWorks()
    {
        return $this->belongsToMany(ServiceWorks::class, 'warranty_claim_service_works', 'warranty_claim_id', 'service_work_id');
    }

    public function serviceWorksAPI()
    {
        return $this->hasMany(WarrantyClaimServiceWork::class, 'warranty_claim_id');
    }

    public function statusColor()
    {
        return match ($this->status) {
            'Новий' => 'blue',
            'Відправлений' => 'purple',
            'Помилковий' => 'red',
            'Розглядається' => 'yellow',
            'Затверджено' => 'green',
            default => 'blue',
        };
    }

    public function servicePartner()
    {
        return $this->belongsTo(UserPartner::class, 'service_partner');
    }
}
