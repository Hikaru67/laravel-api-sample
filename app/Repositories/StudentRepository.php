<?php

namespace App\Repositories;

use App\Models\Student;
use DB;

class StudentRepository extends BaseRepository
{
    /**
     * @return  Student
     */
    public function getModel()
    {
        return Student::class;
    }

    /**
     * @param mixed $query
     * @param mixed $column
     * @param mixed $data
     *
     * @return Query
     */
    public function search($query, $column, $data)
    {
        switch ($column) {
            case 'name':
            case 'address':
            case 'phone':
                return $query->where($column, 'like', '%' . $data . '%');
                break;
            default:
                return $query;
                break;
        }
    }
}
