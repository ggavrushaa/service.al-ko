<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
