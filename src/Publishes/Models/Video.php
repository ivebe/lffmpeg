<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class Video extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'videos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['path', 'title', 'duration', 'description', 'status', 'thumb'];


    /**
     * Get the thumbs for the video.
     */
    public function thumbs()
    {
        return $this->hasMany(Thumb::class);
    }
}
