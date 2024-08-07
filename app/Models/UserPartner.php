<?php

namespace App\Models;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPartner extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'user_partners';

    protected $fillable = [
        'user_id',
    ];

    public function users()
{
    return $this->belongsToMany(User::class, 'users_services_centres', 'user_partner_id', 'user_id')
        ->withPivot('default')
        ->withTimestamps();
}

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'partner_id');
    }
}
