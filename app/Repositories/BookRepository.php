<?php

namespace App\Repositories;

use App\Models\Thesis;
use DB;

class ThesisRepository extends BaseRepository
{
    /**
     * @return  Thesis
     */
    public function getModel()
    {
        return Thesis::class;
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
            case 'phone':
                return $query->where($column, 'like', '%' . $data . '%');
                break;
            default:
                return $query;
                break;
        }
    }
}
