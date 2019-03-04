<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;

/**
 * Class Repository
 */
abstract class Repository implements RepositoryInterface {


    private $app;

    protected $model;

    public function __construct(App $app) {
        $this->app = $app;
        $this->makeModel();
    }

    abstract function model();

    public function all($columns = ['*']) {
        return $this->model->get($columns);
    }

    public function one($columns = ['*']) {
        return $this->model->first($columns);
    } 
 
    public function paginate($perPage = 15, $columns = ['*'], $where = []) {
        return $this->model->paginate($perPage, $columns);
    }
 
    public function create(array $data) {
        return $this->model->create($data);
    }
 
    public function update(array $data, $id, $attribute="id") {
        return $this->model->where($attribute, '=', $id)->update($data);
    }
 
    public function delete($id) {
        return $this->model->destroy($id);
    }
 
    public function find($id, $columns = ['*']) {
        return $this->model->find($id, $columns);
    }
 
    public function findBy($attribute, $value, $columns = ['*']) {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }
 
    public function makeModel() {
        $model = $this->app->make($this->model());
 
        if (!$model instanceof Model)
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
 
        return $this->model = $model->newQuery();
    }

    public function pushCriteria(Criteria $criteria)
    {
        if ($this->preventCriteriaOverwriting) {
            // Find existing criteria
            $key = $this->criteria->search(function ($item) use ($criteria) {
                return (is_object($item) && (get_class($item) == get_class($criteria)));
            });
            // Remove old criteria
            if (is_int($key)) {
                $this->criteria->offsetUnset($key);
            }
        }
        $this->criteria->push($criteria);
        return $this;
    }

    public function applyCriteria()
    {
        if ($this->skipCriteria === true)
            return $this;
        foreach ($this->getCriteria() as $criteria) {
            if ($criteria instanceof Criteria)
                $this->model = $criteria->apply($this->model, $this);
        }
        return $this;
    }

}