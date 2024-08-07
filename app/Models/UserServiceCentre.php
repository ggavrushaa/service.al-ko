<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserServiceCentre extends Model
{
    use HasFactory;

    protected $connection = 'second_db';
    protected $table = 'users_services_centres';

    protected $fillable = [
        'user_id',
        'user_partner_id',
        'default',
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userPartner()
    {
        return $this->belongsTo(UserPartner::class, 'user_partner_id', 'id');
    }
}

