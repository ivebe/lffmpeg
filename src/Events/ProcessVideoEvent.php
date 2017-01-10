<?php

namespace Ivebe\Lffmpeg\Events;

class ProcessVideoEvent extends Event
{
    public $videoID;
    public $caller;
    public $params;

    /**
     * ProcessVideoEvent constructor.
     *
     * @param $videoID integer ID of the video
     * @param $caller string name of the caller class
     * @param $params array any additional parameters that should be passed to the job
     */
    public function __construct($videoID, $caller, $params)
    {
        $this->videoID = $videoID;
        $this->caller  = $caller;
        $this->params  = $params;
    }
}
