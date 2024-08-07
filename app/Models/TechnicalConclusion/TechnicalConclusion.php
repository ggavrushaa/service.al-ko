<?php

namespace App\Models\TechnicalConclusion;

use App\Models\User;
use App\Models\DefectCodes;
use App\Enums\ClaimTypeEnum;
use App\Models\SymptomCodes;
use App\Models\WarrantyClaim;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TechnicalConclusion extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'technical_conclusions';

    protected $fillable = [ 
        'warranty_claim_id', 'date',
        'defect_code', 'symptom_code', 'resolution',  
        'appeal_type', 'conclusion', 'code_1C',
    ];

    protected $casts = [
        'appeal_type' => ClaimTypeEnum::class,
    ];

    public function warrantyClaim()
    {
        return $this->belongsTo(WarrantyClaim::class, 'warranty_claim_id');
    }


    public function defectCode()
    {
        return $this->belongsTo(DefectCodes::class, 'defect_code');
    }

    public function symptomCode()
    {
        return $this->belongsTo(SymptomCodes::class, 'symptom_code');
    }

}
