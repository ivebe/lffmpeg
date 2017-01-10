<?php

namespace Ivebe\Lffmpeg\Helpers;

use Ivebe\Lffmpeg\Factories\JobsFactory;

class Helper
{
    /**
     * @param string $time time in HH:MM:SS format
     * @return integer number of seconds
     */
    public static function time2seconds( $time = '00:00:00' )
    {
        list($hours, $mins, $secs) = explode(':', $time);
        return ($hours * 3600 ) + ($mins * 60 ) + $secs;
    }

    /**
     * Helper function to enable static call to encoding process
     *
     * This is just a helper function you can, and should, implement JobFactory
     * in your code and dispatch job from within your code
     *
     * @param $videoID integer ID of the video.
     */
    public static function runEncoding($videoID)
    {
        $jobFactory = new JobsFactory;
        $job = $jobFactory->createDefaultJob($videoID);

        app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);
    }
}