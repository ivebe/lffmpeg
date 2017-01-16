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
        // Due to inconsistency between pluck and lists in 5.1 and 5.2 and 5.3 doing it this way instead.
        $data = $this->model
            ->select(Config::get('thumb@name'))
            ->where(Config::get('thumb@video_id') , $id)
            ->get()
            ->toArray();

        return array_map(function($item){
            return $item['thumb'];
        }, $data);
    }

    /**
     * @param $id videoID
     */
    public function clear($id)
    {
        return $this->model->where( Config::get('thumb@video_id') , $id)->delete();
    }
}
