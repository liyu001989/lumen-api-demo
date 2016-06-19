<?php

namespace ApiDemo\Repositories;

use ApiDemo\Models\Post;

class PostRepository
{
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function paginate($limit)
    {
        return $this->model
            ->paginate($limit);
    }

    public function where(array $data)
    {
        return $this->model->where($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $attributes)
    {
        $post = $this->model->newInstance($attributes);
        $post->save();
        return $post;
    }

    public function update($id, $attributes)
    {
        $post = $this->model
            ->newInstance()
            ->where('id', $id)
            ->update($attributes);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
}
