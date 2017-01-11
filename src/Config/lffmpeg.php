<?php

return [
    /**
     * Pay attention to content and tmp paths, and ffmpeg binaries
     */

    'paths#tmp_path' => '/tmp',
    'paths#content_path' => '/content',

    /**
     * w - width
     * h - height
     * b - bitrate
     */
    'encoding' => [
        '1080p' => [
            'w' => 1920,
            'h' => 1080,
            'b' => 2000
        ],
        '720p' => [
            'w' => 1280,
            'h' => 720,
            'b' => 1500
        ],
        '480p' => [
            'w' => 854,
            'h' => 480,
            'b' => 1000
        ]
    ],

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