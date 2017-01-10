<?php

namespace Ivebe\Lffmpeg\Repositories\Contracts;

interface IRepository
{
    public function get($id);
    public function update($id, array $data);
    public function create(array $data);
}