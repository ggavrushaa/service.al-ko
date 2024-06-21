<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResolutionTemplate extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'resolution_templates';

    protected $fillable = [
        'code_1C',
        'name',
        'parent_id',
        'is_folder',
        'is_deleted',
        'description',
    ];
}
