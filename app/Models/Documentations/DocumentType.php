<?php

namespace App\Models\Documentations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'document_types';

    protected $fillable = ['name'];
}
