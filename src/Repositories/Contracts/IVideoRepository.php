<?php

namespace Ivebe\Lffmpeg\Repositories\Contracts;

interface IVideoRepository
{
    public function progress();
    public function duration($id);
    public function setProgress($videoID, $quality, $percent, $status = 'ENCODING');
    public function getProgress($videoID, $quality);
}