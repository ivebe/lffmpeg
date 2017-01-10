<?php

namespace Ivebe\Lffmpeg\Repositories;

use Illuminate\Database\Eloquent\Model;
use Ivebe\Lffmpeg\Repositories\Contracts\IRepository;
use Illuminate\Contracts\Container\Container as IContainer;

abstract class EloquentRepository implements IRepository
{
    protected $model;
    abstract protected function model();

    public function __construct(IContainer $app)
    {
        $this->model = $app->make( $this->model() );

        if (!$this->model instanceof Model)
            throw new \Exception("Class {$this->model()} is not an instance of eloquent model.");
    }

    public function get($id)
    {
        return $this->model->find($id);
    }

    public function update($id, array $data)
    {
        return $this->model->find($id)->update($data);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
