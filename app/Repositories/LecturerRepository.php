<?php

namespace App\Repositories;

use App\Models\Lecturer;
use DB;

class LecturerRepository extends BaseRepository
{
    /**
     * @return  Lecturer
     */
    public function getModel()
    {
        return Lecturer::class;
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
