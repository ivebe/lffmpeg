<?php

namespace Ivebe\Lffmpeg\Repositories;

use Ivebe\Lffmpeg\Config\Config;
use Ivebe\Lffmpeg\Repositories\Contracts\IThumbRepository;

class ThumbRepository extends EloquentRepository implements IThumbRepository
{
    protected function model()
    {
        return Config::get('thumb#model');
    }


    /**
     * Override default EloquentRepository get method
     *
     * @param $id VideoID
     * @return mixed
     */
    public function get($id)
    {
        return $this->model->where( Config::get('thumb@video_id'), $id)->first();
    }

    /**
     * @param $id videoID
     * @return string first thumb found
     */
    public function thumb($id)
    {
        //return first instance, which is exactly what we need
        $thumb = $this->get($id);

        if($thumb)
            return $thumb->{Config::get('thumb@name')};

        return null;
    }

    /**
     * @param $id
     * @return array
     */
    public function thumbs($id)
    {
        return $this->model->where( Config::get('thumb@video_id') , $id)->lists( Config::get('thumb@name') )->toArray();
    }

    /**
     * @param $id videoID
     */
    public function clear($id)
    {
        return $this->model->where( Config::get('thumb@video_id') , $id)->delete();
    }
}
