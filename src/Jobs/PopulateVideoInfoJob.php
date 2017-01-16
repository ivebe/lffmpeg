<?php

namespace Ivebe\Lffmpeg\Jobs;

use Ivebe\Lffmpeg\Config\Config;
use Ivebe\Lffmpeg\Libs\Contracts\IEncodingLib;
use Ivebe\Lffmpeg\Services\Contracts\IVideoService;
use Psr\Log\LoggerInterface;

class PopulateVideoInfoJob extends BasicVideoJob
{
    public function __construct($videoID)
    {
        parent::__construct($videoID);
    }

    /**
     * @param IVideoService $videoService
     * @param IEncodingLib $encodingLib
     * @param LoggerInterface $log
     */
    public function handle(IVideoService $videoService,
                           IEncodingLib $encodingLib,
                           LoggerInterface $log)
    {
        $this->init($videoService, $encodingLib, $log);

        try {
            $this->clearPreviousEncodingProgress();
            $this->setDuration();
            $this->eventFinished();
        } catch (\Exception $e) {
            $this->log->error("{$this->videoID} populating data failed: " . $e->getMessage());
        }
    }

    /**
     * Set duration of the video in the repository
     */
    private function setDuration()
    {
        $tmpPath  = $this->videoService->getVideoTmpPathWithFilename($this->videoID);
        $duration = $this->encodingLib->getDuration($tmpPath);

        $repo = $this->videoService->getVideoRepository();
        $repo->update($this->videoID, [ Config::get('video@duration') => $duration]);
    }

    private function clearPreviousEncodingProgress()
    {
        $this->videoService->getVideoRepository()->clearProgress($this->videoID);
    }
}
