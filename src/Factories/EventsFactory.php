<?php

namespace Ivebe\Lffmpeg\Factories;

use Ivebe\Lffmpeg\Events\ProcessVideoEvent;
use Ivebe\Lffmpeg\Factories\Contracts\IEventsFactory;

class EventsFactory implements IEventsFactory
{

    /**
     * @param $videoID integer ID of the video
     * @param $caller string reference to calling class
     * @param array $params
     * @return ProcessVideoEvent
     */
    public static function createProcessVideoEvent($videoID, $caller, $params = [])
    {
        return new ProcessVideoEvent($videoID, $caller, $params);
    }

}