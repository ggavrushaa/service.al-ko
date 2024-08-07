<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';
    protected $connection = 'mysql';

    public function userPartner()
    {
        return $this->belongsTo(UserPartner::class, 'partner_id');
    }
}
