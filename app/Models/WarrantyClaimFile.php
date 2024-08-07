<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaimFile extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'warranty_claim_files';

    protected $fillable = [
        'warranty_claim_id', 'path', 'filename',
    ];

    public function warrantyClaim()
    {
        return $this->belongsTo(WarrantyClaim::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
