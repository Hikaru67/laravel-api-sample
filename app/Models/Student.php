<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
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
        return $this->hasOne(Thesis::class);
    }
}
