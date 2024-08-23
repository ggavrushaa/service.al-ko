<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\TechnicalConclusion\TechnicalConclusion;

class SymptomCodes extends Model
{
    use HasFactory;

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


    protected $connection = 'second_db';
    protected $table = 'symptom_codes';

    public $timestamps = false;

    public function technicalConclusions()
    {
        return $this->hasMany(TechnicalConclusion::class, 'symptom_code');
    }

    public function children()
    {
        return $this->hasMany(SymptomCodes::class, 'parent_id', 'code_1C');
    }
}
