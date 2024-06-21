<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
