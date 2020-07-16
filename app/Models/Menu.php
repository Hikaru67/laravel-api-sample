<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Menu extends Model
{
    protected $guard_name = 'web';

    use HasRoles;

    protected $fillable = [
        'title',
        'link',
        'icon',
        'parent_id',
        'position',
    ];

    public function menus()
    {
        return $this->hasMany(self::class, 'parent_id')->with('menus');
    }
}
