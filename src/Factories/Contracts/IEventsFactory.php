<?php

namespace Ivebe\Lffmpeg\Factories\Contracts;

interface IEventsFactory
{
    /**
     * @param $videoID integer ID of the video
     * @param $caller string reference to calling class
     * @param array $params
     * @return ProcessVideoEvent
     */
    public static function createProcessVideoEvent($videoID, $caller, $params = []);
}