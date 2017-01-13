<?php

namespace Ivebe\Lffmpeg\Jobs;

use Illuminate\Support\Facades\File;
use Ivebe\Lffmpeg\Config\Config;
use Ivebe\Lffmpeg\Helpers\Helper;
use Ivebe\Lffmpeg\Libs\Contracts\IEncodingLib;
use Ivebe\Lffmpeg\Libs\Contracts\IImageLib;
use Ivebe\Lffmpeg\Services\Contracts\IVideoService;
use Psr\Log\LoggerInterface;

class GenerateThumbsJob extends BasicVideoJob
{
    private $tmpPath;
    private $destinationPath;
    private $imageLib;

    public function __construct($videoID)
    {
        parent::__construct($videoID);
    }

    /**
     * @param IVideoService $videoService
     * @param IEncodingLib $encodingLib
     * @param LoggerInterface $log
     * @param IImageLib $imageLib
     */
    public function handle(IVideoService $videoService,
                           IEncodingLib $encodingLib,
                           LoggerInterface $log,
                           IImageLib $imageLib)
    {
        $this->initialize($videoService, $encodingLib, $log, $imageLib);

        try {
            $videoService->getVideoRepository()->update($this->videoID, [
                Config::get('video@status') => 1
            ]);

            $this->clear();
            $this->generateThumbs();
            $this->resizeThumbs();
            $this->moveTmpThumbs();

            $this->eventFinished();
        } catch (\Exception $e) {
            $this->log->error("{$this->videoID} generating thumbs failed: " . $e->getMessage());
        }
    }

    /**
     * @param IVideoService $videoService
     * @param IEncodingLib $encodingLib
     * @param LoggerInterface $log
     * @param IImageLib $imageLib
     */
    private function initialize(IVideoService $videoService,
                                IEncodingLib $encodingLib,
                                LoggerInterface $log,
                                IImageLib $imageLib)
    {
        parent::init($videoService, $encodingLib, $log);
        $this->imageLib = $imageLib;

        $this->tmpPath = $this->videoService->getThumbsTmpPath($this->videoID);
        $this->destinationPath = $this->videoService->getThumbsPath($this->videoID);
    }

    private function clear()
    {
        if (File::isDirectory($this->destinationPath))
            File::deleteDirectory($this->destinationPath);

        if (File::isDirectory($this->tmpPath))
            File::deleteDirectory($this->tmpPath);

        $this->videoService->getThumbRepository()->clear($this->videoID);
    }

    /**
     * extract thumbs from the video
     */
    private function generateThumbs()
    {

        if (!file_exists($this->tmpPath))
            mkdir($this->tmpPath, 0777, true);

        $videoRepo = $this->videoService->getVideoRepository();
        $thumbRepo = $this->videoService->getThumbRepository();

        $seconds = Helper::time2seconds($videoRepo->duration($this->videoID));

        $perSecond = Config::get('thumb#interval');

        //1 thumb every 10 seconds
        $numOfThumbs = (int)($seconds / $perSecond);

        for ($i = 1; $i <= $numOfThumbs; $i++) {

            $name     = sprintf( Config::get('thumb#name-format'), $i );
            $img      = $this->tmpPath . '/' . $name;
            $interval = $i * $perSecond;

            $this->encodingLib->saveFrame($this->videoService->getVideoTmpPathWithFilename($this->videoID), $img, $interval);

            $thumbRepo->create([
                Config::get('thumb@video_id') => $this->videoID,
                Config::get('thumb@name') => $name,
                Config::get('thumb@scene_time') => $interval
            ]);
        }

        $thumb = $thumbRepo->thumb($this->videoID);
        $videoRepo->update($this->videoID, [Config::get('video@default_thumb')  => $thumb]);
    }

    /**
     * resize thumbs to desired size
     */
    private function resizeThumbs()
    {
        $thumbRepo = $this->videoService->getThumbRepository();

        foreach ($thumbRepo->thumbs($this->videoID) as $thumb)
            $this->imageLib->thumb($this->tmpPath . '/' . $thumb, Config::get('thumb#width'), Config::get('thumb#height'));
    }

    /**
     * Move temporary files to desired location.
     * This way if we use some kind of CDN, we won't accidentally cache wrong files
     */
    private function moveTmpThumbs()
    {
        if (File::isDirectory($this->destinationPath))
            File::deleteDirectory($this->destinationPath);

        File::copyDirectory($this->tmpPath, $this->destinationPath);
        File::deleteDirectory($this->tmpPath);
    }
}
