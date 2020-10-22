<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    const ADMIN = 'admin';

    protected $fillable = [
        'name',
        'guard_name',
    ];
}
