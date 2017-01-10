<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncodingProgress extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'encoding_progress';

    /**
     * The hidden fields.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['video_id', 'percent', 'status', 'quality'];

    /**
     * Get the video that owns the thumb.
     */
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
