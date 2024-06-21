<?php

namespace App\Models\TechnicalConclusion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TechnicalConclusion\TechnicalConclusion;

class TechnicalConclusionFile extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'technical_conclusion_files';

    protected $fillable = [
        'path',
        'filename',
        'technical_conclusion_id',
    ];

    public function technicalConclusion()
    {
        return $this->belongsTo(TechnicalConclusion::class, 'technical_conclusion_id');
    }
}
