<?php

namespace Ivebe\Lffmpeg\Libs\Contracts;

interface IImageLib
{
    public function thumb($file, $w, $h);
}