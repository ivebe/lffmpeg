<?php

namespace Ivebe\Lffmpeg\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ivebe\Lffmpeg\Factories\EventsFactory;
use Ivebe\Lffmpeg\Libs\Contracts\IEncodingLib;
use Ivebe\Lffmpeg\Services\Contracts\IVideoService;
use Psr\Log\LoggerInterface;

class BasicVideoJob implements SelfHandling, ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected $videoID;
    protected $videoService;
    protected $log;
    protected $encodingLib;

    protected function __construct($videoID)
    {
        $this->videoID = $videoID;
    }

    /**
     * Since job is serialized, we put DI into handle method, and call it over init function
     *
     * @param \Ivebe\Lffmpeg\Services\Contracts\IVideoService $videoService
     * @param \Ivebe\Lffmpeg\Libs\Contracts\IEncodingLib $encodingLib
     * @param \Psr\Log\LoggerInterface $log
     *
     */
    protected function init(IVideoService $videoService,
                            IEncodingLib $encodingLib,
                            LoggerInterface $log)
    {
        $this->videoService = $videoService;
        $this->encodingLib  = $encodingLib;
        $this->log          = $log;
    }

    protected function eventFinished($params = [])
    {
        event( EventsFactory::createProcessVideoEvent($this->videoID, get_class($this), $params) );
    }
}
