<?php

namespace Ivebe\Lffmpeg\Factories;

use Ivebe\Lffmpeg\Factories\Contracts\IJobsFactory;
use Ivebe\Lffmpeg\Jobs\EncodeJob;
use Ivebe\Lffmpeg\Jobs\GenerateThumbsJob;
use Ivebe\Lffmpeg\Jobs\PopulateVideoInfoJob;

class JobsFactory implements IJobsFactory
{

    /**
     * @return string job class reference
     */
    public function getDefaultJobInstance()
    {
        //TODO: make publish tag for jobs too if someone wants to change them
        return PopulateVideoInfoJob::class;
    }

    /**
     * @return string job class reference
     */
    public function getThumbsJobInstance()
    {
        //TODO: make publish tag for jobs too if someone wants to change them
        return GenerateThumbsJob::class;
    }

    /**
     * @return string job class reference
     */
    public function getEncodeJobInstance()
    {
        //TODO: make publish tag for jobs too if someone wants to change them
        return EncodeJob::class;
    }



    /**
     * @param $vID integer videoID
     * @return mixed instance of default Job
     */
    public function createDefaultJob($vID)
    {
        $instance = $this->getDefaultJobInstance();
        return new $instance( $vID );
    }

    /**
     * @param $vID integer videoID
     * @return mixed instance of thumbs Job
     */
    public function createThumbsJob($vID)
    {
        $instance = $this->getThumbsJobInstance();
        return new $instance( $vID );
    }

    /**
     * @param $vID integer videoID
     * @param $quality string quality string. [1080p, 720p, 480p]
     * @return mixed instance of encode Job
     */
    public function createEncodeJob($vID, $quality)
    {
        $instance = $this->getEncodeJobInstance();
        return new $instance( $vID, $quality );
    }
}