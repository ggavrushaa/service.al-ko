<?php

namespace App\Models\TechnicalConclusion;

use App\Models\WarrantyClaimSpareParts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalConclusionSparePart extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'technical_conclusion_spare_parts';

    protected $fillable = [
        'technical_conclusion_id',
        'spare_part_id',
    ];

    public function sparePart()
    {
        return $this->belongsTo(WarrantyClaimSpareParts::class, 'spare_part_id');
    }
}
