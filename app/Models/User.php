<?php

namespace App\Models;

use App\Models\WarrantyClaim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'second_db';
    protected $table = 'users';
    protected $dates = ['last_login_at'];

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'middle_name',
        'company_name',
        'phone',
        'country_code',
        'last_login_ip',
        'first_name_ru',
        'last_login_time',
        'status',
        'role_id',
    ];

    public $timestamps = false;

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function warrantyClaims()
    {
        $this->hasMany(WarrantyClaim::class);
    }

    public function setPasswordAttribute($value)
    {
        if (!Hash::needsRehash($value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    public function defaultServiceCentre()
    {
        return DB::connection('second_db')->table('users_services_centres')
            ->where('user_id', $this->id)
            ->where('default', 1)
            ->first();
    }

    public function serviceCentres()
    {
        return DB::connection('second_db')->table('users_services_centres')
            ->where('user_id', $this->id)
            ->get();
    }

}
