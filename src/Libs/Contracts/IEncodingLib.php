<?php

namespace Ivebe\Lffmpeg\Libs\Contracts;

interface IEncodingLib
{
    public function getDuration($videoPath);
    public function saveFrame($src, $dst, $time);
    public function encode($src, $dst, $w, $h, $b, $progressFunc);
}