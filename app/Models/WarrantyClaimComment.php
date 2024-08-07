<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaimComment extends Model
{
    use HasFactory;
    protected $table = 'warranty_claim_comments';
    protected $connection = 'second_db';

    protected $fillable = [
        
    ];



}
