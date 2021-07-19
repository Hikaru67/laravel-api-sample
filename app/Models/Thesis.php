<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    protected $table = 'theses';

    protected $fillable = [
        'name',
        'description',
        'attaches',
        'student_id',
        'lecturer_id',
    ];

    public $selectable = [
        '*',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}
