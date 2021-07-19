<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'specialized',
    ];

    public $selectable = [
        '*',
    ];

    public function theses()
    {
        return $this->hasMany(Thesis::class);
    }
}
