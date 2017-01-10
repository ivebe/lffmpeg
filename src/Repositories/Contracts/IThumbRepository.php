<?php

namespace Ivebe\Lffmpeg\Repositories\Contracts;

interface IThumbRepository
{
    public function thumb($id);
    public function thumbs($id);
    public function clear($id);
}