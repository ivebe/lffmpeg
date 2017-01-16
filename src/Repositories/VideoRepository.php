<?php

namespace Ivebe\Lffmpeg\Repositories;

use Illuminate\Database\Eloquent\Model;
use Ivebe\Lffmpeg\Config\Config;
use Ivebe\Lffmpeg\Repositories\Contracts\IVideoRepository;
use Illuminate\Contracts\Container\Container as IContainer;


class VideoRepository extends EloquentRepository implements IVideoRepository
{

    protected $progress;

    public function  __construct(IContainer $app)
    {
        parent::__construct($app);

        $this->progress = $app->make( $this->progress() );

        if (!$this->progress instanceof Model)
            throw new \Exception("Class {$this->progress()} is not an instance of eloquent model.");
    }

    protected function model()
    {
        return Config::get('video#model');
    }

    public function progress()
    {
        return Config::get('progress#model');
    }

    public function duration($id)
    {
        $vid = $this->get($id);

        if(!$vid)
            return null;

        return $vid->{Config::get('video@duration')};
    }

    public function status($id)
    {
        $vid = $this->get($id);

        if(!$vid)
            return null;

        return $vid->{Config::get('video@status')};
    }

    public function clearProgress($videoID)
    {
        return $this->progress->where( Config::get('progress@video_id'), $videoID )->delete();
    }

    public function setProgress($videoID, $quality, $percent, $status = 'ENCODING')
    {
        $encProgress = $this->progress
            ->where( Config::get('progress@video_id'), $videoID )
            ->where( Config::get('progress@quality'), $quality )
            ->first();

        if(!$encProgress)
        {
            $this->progress->create([
                Config::get('progress@status') => $status,
                Config::get('progress@percent') => $percent,
                Config::get('progress@video_id') => $videoID,
                Config::get('progress@quality') => $quality
            ]);
        }
        else {

            $params[Config::get('progress@status')] = $status;

            if ($percent)
                $params[Config::get('progress@percent')] = $percent;

            $encProgress->update($params);
        }
    }

    /**
     * @param $videoID
     * @param null $quality if null return all qualities, otherwise return one requested
     * @return mixed
     */
    public function getProgress($videoID, $quality = null)
    {
        //get all qualities for video
        if(is_null($quality))
            return $this->progress
                ->select(Config::get('progress@quality'), Config::get('progress@percent'), Config::get('progress@status'))
                ->where(Config::get('progress@video_id'), $videoID)
                ->orderBy(Config::get('progress@quality'), 'DESC')
                ->get()
                ->toArray();

        //get specific quality percentage
        return $this->progress
            ->where(Config::get('progress@video_id'), $videoID)
            ->where(Config::get('progress@quality'), $quality)
            ->pluck(Config::get('progress@percent'));
    }

}
