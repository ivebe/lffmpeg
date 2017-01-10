<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thumb extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'thumbs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['video_id', 'thumb', 'scene_time'];

    /**
     * Get the video that owns the thumb.
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
