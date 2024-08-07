<?php

namespace App\Models;

use App\Traits\BelongsToManyCustomConnectionTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use BelongsToManyCustomConnectionTrait;
    protected  $table = 'roles';
    protected $connection = 'second_db';
    protected $fillable = ['name'];

}
