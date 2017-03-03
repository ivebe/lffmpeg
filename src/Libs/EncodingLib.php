<?php

namespace Ivebe\Lffmpeg\Libs;

use FFMpeg\FFMpeg;
use Ivebe\Lffmpeg\Config\Config;
use Ivebe\Lffmpeg\Libs\Contracts\IEncodingLib;
use Psr\Log\LoggerInterface;

class EncodingLib implements IEncodingLib
{
    protected $encodingLib = null;
    protected $videos = [];

    public function __construct(LoggerInterface $log)
    {
        if(is_null($this->encodingLib)) {

            $this->encodingLib = FFMpeg::create([
                'ffmpeg.binaries'  => Config::get('encoding#ffmpeg_binaries'),
                'ffprobe.binaries' => Config::get('encoding#ffprobe_binaries'),
                'ffmpeg.threads'   => Config::get('encoding#threads'),
                'timeout'          => 0
            ], $log);
        }

        return $this->encodingLib;
    }

    protected function getVideo($videoPath)
    {
        if(!isset($this->videos[$videoPath]))
            $this->videos[$videoPath] = $this->encodingLib->open( $videoPath );

        return $this->videos[$videoPath];
    }

    public function getDuration($videoPath)
    {
        $duration_string = $this->encodingLib->getFFProbe()
            ->format($videoPath)
            ->get('duration');

        $duration_float = floatval($duration_string);
        $duration = date("H:i:s", $duration_float);

        return $duration;
    }

    public function saveFrame($src, $dst, $time)
    {
        $this->getVideo($src)->frame( \FFMpeg\Coordinate\TimeCode::fromSeconds($time) )->save($dst);
    }

    public function encode($src, $dst, $w, $h, $b, $progressFunc)
    {
        $format = new \FFMpeg\Format\Video\X264('libfdk_aac', 'libx264');
        $format->setAudioChannels(2)->setAudioKiloBitrate(256);

        $format->on('progress', $progressFunc);

        $format->setKiloBitrate($b);
        $this->getVideo($src)->filters()->resize(new \FFMpeg\Coordinate\Dimension($w, $h));
        $this->getVideo($src)->save($format, $dst);
    }
}