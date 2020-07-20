<?php

namespace App\Repositories;

use App\Exceptions\DDException;
use Carbon\Carbon;
use DB;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Str;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct()
    {
        $this->app = new App();
        $this->setModelClass();
    }

    abstract public function getModel();

    /**
     * @return Model
     */
    public function setModelClass()
    {
        $model = $this->app->make($this->getModel());
        if (! $model instanceof Model) {
            throw new DDException("Class {$this->getModel()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Get list model.
     *
     * @param mixed $request
     * @param array $relations
     *
     * @return array $entities
     */
    public function list($data, $relations = [], $relationCounts = [])
    {
        $data = collect($data);

        $config = config('constant');

        // select list column
        $entities = $this->model->select($this->model->selectable ?? ['*']);

        // load realtion counts
        if (count($relationCounts)) {
            $entities = $entities->withCount($relationCounts);
        }

        // load relations
        if (count($relations)) {
            $entities = $entities->with($relations);
        }

        // filter list by condition
        $condition = $data->has('condition') && $config['encode_condition'] ? (array) json_decode(base64_decode($data['condition'])) : $data;
        if (count($condition) && method_exists($this, 'search')) {
            foreach ($condition as $key => $value) {
                $entities = $this->search($entities, $key, $value);
            }
        }

        // order list
        $orderBy = $data->has('sort') && in_array($data['sort'], $this->model->sortable) ? $data['sort'] : $this->model->getKeyName();
        $entities = $entities->orderBy($orderBy, $data->has('sortType') && $data['sortType'] == 1 ? 'asc' : 'desc');

        // limit result
        $limit = $data->has('limit') ? (int) $data['limit'] : $config['paginate'];
        if ($limit) {
            return $entities->paginate($limit);
        }

        return $entities->get();
    }

    /**
     * Create model.
     *
     * @param array $data
     *
     * @return Model
     */
    public function create($data = [])
    {
        return $this->model->create($data);
    }

    /**
     * Get model detail.
     *
     * @param Model $entity
     *
     * @return Model
     */
    public function detail(Model $entity, $relations = [])
    {
        if (count($relations)) {
            return $entity->load($relations);
        }

        return $entity;
    }

    /**
     * Update model.
     *
     * @param Model $entity
     * @param array $data
     *
     * @return Model
     */
    public function update(Model $entity, $data = [])
    {
        $entity->update($data);

        return $entity;
    }

    /**
     * Update or create model.
     *
     * @param array $condition
     * @param array $data
     *
     * @return Model
     */
    public function updateOrCreate($condition = [], $data = [])
    {
        return $this->model->updateOrCreate($condition, $data);
    }

    /**
     * Delete model.
     *
     * @param Model $entity
     *
     * @return void
     */
    public function delete(Model $entity)
    {
        $entity->delete();
    }

    /**
     * Synchro model relation with data.
     *
     * @param Model $entity
     * @param mixed $relation
     * @param array $data
     *
     * @return void
     */
    public function sync(Model $entity, $relation, $data = [])
    {
        $entity->$relation()->sync($data);
    }

    /**
     * Get model count.
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Get model total.
     *
     * @return int
     */
    public function total($field)
    {
        return $this->model->sum($field);
    }

    /**
     * Insert multiple values.
     *
     * @return int
     */
    public function insert($data)
    {
        $data = array_map(function ($item) {
            $item['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $item['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

            return $item;
        }, $data);

        return $this->model->insert($data);
    }

    /**
     * Group model by column.
     *
     * @param string $field
     *
     * @return void
     */
    public function groupBy($field)
    {
        $raw = $field.', count('.$field.') as '.$field.'_count';

        return $this->model->select(DB::raw($raw))->groupBy($field)->get();
    }

    /**
     * Find model by id.
     *
     * @param mixed $id
     * @param array $relations
     *
     * @return Model
     */
    public function find($id, $relations = [])
    {
        $entity = $model->findOrFail($id);

        if (count($relations)) {
            return $entity->load($relations);
        }

        return $entity;
    }

    /**
     * Find by condition .
     *
     * @param mixed $request
     * @param array $relations
     *
     * @return object $entities
     */
    public function findByCondition($condition, $relations = [])
    {
        $entities = $this->model->select($this->model->selectable);

        if (count($relations)) {
            $entities = $entities->with($relations);
        }

        if (count($condition)) {
            foreach ($condition as $key => $value) {
                $entities = $this->search($entities, $key, $value);
            }
        }

        return $entities;
    }

    /**
     * Cache the query result.
     *
     * @param string $method
     * @param mixed ...$params
     *
     * @return mixed cached query result
     */
    public function cache($method, ...$params)
    {
        if (! method_exists($this, $method)) {
            throw new DDException("Method doesn't exist");
        }
        $name = Str::singular($this->model->getTable()).'_'.$method;
        $cacheByKey = config('constant.cache_expired.'.$name);
        $expired = $cacheByKey ? $cacheByKey : config('constant.cache_expired.default', 0);

        return cache()->remember($name, $expired, function () use ($method, $params) {
            return $this->$method(...$params);
        });
    }
}
