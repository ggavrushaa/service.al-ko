<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TechnicalConclusion\TechnicalConclusion;

class DefectCodes extends Model
{
    use HasFactory;
    protected $connection = 'second_db';
    protected $table = 'defect_codes';

    protected $fillable = [
        'id',
        'code_1C',
        'name',
        'parent_id',
        'is_folder',
        'is_deleted',
        'created',
        'edited',
    ];

    public function technicalConclusions()
{
    return $this->hasMany(TechnicalConclusion::class, 'defect_code');
}

}
