<?php

return [
    /**
     * Pay attention to content and tmp paths, and ffmpeg binaries
     */

    'paths#tmp_path' => '/Users/dexa/vod/tmp',
    'paths#content_path' => '/Users/dexa/vod/content',

    'encoding#1080p' => 2000,
    'encoding#720p'  => 1500,
    'encoding#480p'  => 1000,

    'encoding#ffmpeg_binaries'  => '/usr/local/bin/ffmpeg',
    'encoding#ffprobe_binaries' => '/usr/local/bin/ffprobe',
    'encoding#threads'          => 1,


    'video#model'         => \App\Video::class,
    'video@duration'      => 'duration',
    'video@status'        => 'status',
    'video@default_thumb' => 'thumb',

    'thumb#model'       => \App\Thumb::class,
    'thumb#interval'    => 10,
    'thumb#name-format' => "thumb_%s.jpg",
    'thumb@video_id'    => 'video_id',
    'thumb@name'        => 'thumb',
    'thumb@scene_time'  => 'scene_time',

    'progress#model'    => \App\EncodingProgress::class,
    'progress@video_id' => 'video_id',
    'progress@quality'  => 'quality',
    'progress@status'   => 'status',
    'progress@percent'  => 'percent',
];