<?php

namespace App\Repositories\Eloquent;

abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = app()->make($this->model());
    }

    abstract public function model();

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
        return $this->model->find($id);
    }

    public function create(array $attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();

        return $model;
    }

    public function update($id, array $attributes)
    {
        // 感觉不太对
        $model = $this->model->find($id);
        $model->fill($attributes)->save();

        return $model;
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

    public function get()
    {
        return $this->model->get();
    }

    public function limit($limit)
    {
        $this->model = $this->model->limit($limit);

        return $this;
    }
}
