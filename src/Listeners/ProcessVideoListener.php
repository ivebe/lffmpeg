<?php

namespace Ivebe\Lffmpeg\Listeners;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Ivebe\Lffmpeg\Config\Config;
use Ivebe\Lffmpeg\Events\Contracts\IProcessVideoEvent;
use Ivebe\Lffmpeg\Factories\Contracts\IJobsFactory;
use Ivebe\Lffmpeg\Helpers\Consts;
use Ivebe\Lffmpeg\Services\Contracts\IVideoService;

class ProcessVideoListener
{
    use DispatchesJobs;

    private $videoService;
    private $jobsFactory;

    /**
     * ProcessVideoListener constructor.
     *
     * @param IVideoService $videoService
     * @param IJobsFactory $jobsFactory
     */
    public function __construct(IVideoService $videoService, IJobsFactory $jobsFactory)
    {
        $this->videoService = $videoService;
        $this->jobsFactory  = $jobsFactory;
    }

    /**
     * @param IProcessVideoEvent $event
     */
    public function handle(IProcessVideoEvent $event)
    {
        switch ($event->caller) {
            case $this->jobsFactory->getDefaultJobInstance():

                $this->videoService->getVideoRepository()->update($event->videoID, [
                    Config::get('video@status') => Consts::ENCODING_THUMBS
                ]);

                $job = $this->jobsFactory->createThumbsJob($event->videoID);
                $this->dispatch($job);
                break;

            case $this->jobsFactory->getThumbsJobInstance():

                $this->videoService->getVideoRepository()->update($event->videoID, [
                    Config::get('video@status') => Consts::ENCODING_VIDEO
                ]);

                //detect best possible quality
                $quality = $this->videoService->detectBestQuality($event->videoID);

                $job = $this->jobsFactory->createEncodeJob($event->videoID, $quality);
                $this->dispatch($job);
                break;

            case $this->jobsFactory->getEncodeJobInstance():

                $quality = $this->videoService->getNextLowerQuality($event->params['quality']);

                if ($quality) {
                    $job = $this->jobsFactory->createEncodeJob($event->videoID, $quality);
                    $this->dispatch($job);
                } else {
                    $this->videoService->getVideoRepository()->update($event->videoID, [
                        Config::get('video@status') => Consts::ENCODING_DONE
                    ]);
                }
                break;
        }
    }
}
