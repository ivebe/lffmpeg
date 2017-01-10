<?php

namespace Ivebe\Lffmpeg\Events\Contracts;

interface IProcessVideoEvent
{
    public function __construct($videoID, $caller, $params);
}