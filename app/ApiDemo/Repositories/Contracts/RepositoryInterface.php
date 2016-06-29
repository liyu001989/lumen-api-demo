<?php

namespace ApiDemo\Repositories\Contracts;

interface RepositoryInterface
{
    public function paginate($limit = null);

    public function where(array $data);

    public function first();

    public function find($id);

    public function create(array $attributes);

    public function update(int $id, array $attributes);

    public function destroy($id);
}
