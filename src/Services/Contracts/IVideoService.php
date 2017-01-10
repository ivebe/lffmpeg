<?php

namespace Ivebe\Lffmpeg\Services\Contracts;

interface IVideoService
{
    public function getVideoRepository();

    public function getThumbRepository();

    public function getVideoPathWithFilename($id, $quality = null);

    public function getVideosPath($id);

    public function getVideosTmpPath($id);

    public function getThumbsPath($id);

    public function getThumbsTmpPath($id);

    public function getVideoTmpPathWithFilename($id, $quality = null);
}