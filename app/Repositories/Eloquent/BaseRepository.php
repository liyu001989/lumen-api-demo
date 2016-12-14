<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->makeModel();
    }

    abstract public function model();

    protected function makeModel()
    {
        return $this->model = app()->make($this->model());
    }

    protected function resetModel()
    {
        return $this->makeModel();
    }

    public function paginate($limit = null)
    {
        return $this->model
            ->paginate($limit);
    }

    public function where(array $data)
    {
        $this->model = $this->model->where($data);

        return $this;
    }

    public function first()
    {
        return $this->model->first();
    }

    public function find($id)
    {
        $model = $this->model->find($id);
        $this->resetModel();

        return $model;
    }

    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();

        $this->resetModel();

        return $model;
    }

    public function update($id, array $attributes)
    {
        $model = $id instanceof Model ? $id : $this->model->find($id);

        $model->fill($attributes)->save();

        $this->resetModel();

        return $model;
    }

    public function destroy($id)
    {
        $result = $this->model->destroy($id);
        $this->resetModel();

        return $result;
    }

    public function get()
    {
        $result = $this->model->get();
        $this->resetModel();

        return $result;
    }

    public function limit($limit)
    {
        $this->model = $this->model->limit($limit);

        return $this;
    }
}
