<?php

namespace Ivebe\Lffmpeg\Services;

use Ivebe\Lffmpeg\Config\Config;
use Ivebe\Lffmpeg\Helpers\Consts;
use Ivebe\Lffmpeg\Repositories\Contracts\IThumbRepository;
use Ivebe\Lffmpeg\Repositories\Contracts\IVideoRepository;
use Ivebe\Lffmpeg\Services\Contracts\IVideoService;
use Psr\Log\LoggerInterface;

class VideoService implements IVideoService
{
    private $videoRepository;
    private $thumbRepository;
    private $log;

    /**
     * VideoService constructor.
     * @param IVideoRepository $videoRepository
     * @param IThumbRepository $thumbRepository
     * @param LoggerInterface $log
     */
    public function __construct(IVideoRepository $videoRepository, IThumbRepository $thumbRepository, LoggerInterface $log)
    {
        $this->videoRepository = $videoRepository;
        $this->thumbRepository = $thumbRepository;
        $this->log = $log;
    }

    public function getVideoRepository()
    {
        return $this->videoRepository;
    }

    public function getThumbRepository()
    {
        return $this->thumbRepository;
    }

    private function validateArray($type, $allowed)
    {
        if (!in_array($type, $allowed))
            throw new \Exception("Unknown '{$type}', only [" . implode(', ', $allowed) . "] are allowed");
    }

    /**
     * @param $id
     * @param string $type {'videos' | 'thumbs'}
     * @param bool $isTmp
     * @param bool $includeFilename
     * @param null $quality {'1080p', '720p', '480p'}
     * @return string
     * @throws \Exception
     */
    private function generatePath($id, $type = 'videos', $isTmp = false, $includeFilename = true, $quality = null)
    {

        $this->validateArray($type, ['videos', 'thumbs']);

        $video = $this->videoRepository->get($id);

        if ($isTmp)
            $contentPath = Config::get('paths#tmp_path');
        else
            $contentPath = Config::get('paths#content_path');

        $name = "";

        if ($includeFilename)
            $name = $video->path;

        if (!is_null($quality)) {

            $this->validateArray($quality, ['1080p', '720p', '480p']);
            $name = $quality . '_' . $name;
        }

        return sprintf("%s/%s/%s/%s",
            $contentPath,
            $video->id,
            $type,
            $name
        );
    }

    public function getVideoPathWithFilename($id, $quality = null)
    {
        return $this->generatePath($id, 'videos', false, true, $quality);
    }

    public function getVideoTmpPathWithFilename($id, $quality = null)
    {
        return $this->generatePath($id, 'videos', true, true, $quality);
    }

    public function getVideosPath($id)
    {
        return $this->generatePath($id, 'videos', false, false);
    }

    public function getVideosTmpPath($id)
    {
        return $this->generatePath($id, 'videos', true, false);
    }

    public function getThumbsPath($id)
    {
        return $this->generatePath($id, 'thumbs', false, false);
    }

    public function getThumbsTmpPath($id)
    {
        return $this->generatePath($id, 'thumbs', true, false);
    }

    public function detectBestQuality($id)
    {
        $dimension = $this->getFFProbe()
            ->streams( $this->getVideoTmpPathWithFilename($id) )
            ->videos()
            ->first()
            ->getDimensions();

        if($dimension->getHeight() >= 1080)
            return Consts::_1080p;

        if($dimension->getHeight() >= 720)
            return Consts::_720p;

        //in all other cases return smallest resolution
        return Consts::_480p;
    }

    public function getNextLowerQuality($quality)
    {
        switch($quality)
        {
            case Consts::_1080p:
                return Consts::_720p;
                break;
            case Consts::_720p:
                return Consts::_480p;
                break;
            default:
                return null;
                break;
        }
    }


    private $ffmpeg = null;

    public function getFFMpeg()
    {
        if(is_null($this->ffmpeg)) {

            $this->ffmpeg = \FFMpeg\FFMpeg::create([
                'ffmpeg.binaries'  => Config::get('encoding#ffmpeg_binaries'),
                'ffprobe.binaries' => Config::get('encoding#ffprobe_binaries'),
                'ffmpeg.threads'   => Config::get('encoding#threads'),
                'timeout' => 0
            ], $this->log);
        }

        return $this->ffmpeg;
    }

    public function getFFProbe()
    {
        if(is_null($this->ffmpeg)) {
            $this->getFFMpeg();
        }

        return $this->getFFMpeg()->getFFProbe();
    }
}