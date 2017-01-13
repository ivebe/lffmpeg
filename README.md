# LFFMPEG
PHP-FFMpeg implementation in Laravel with progress bar. Plug and play.

### Installation

Install using composer.

```sh
composer require ivebe/lffmpeg
```

After you install it add service provider to the providers array in the `config/app.php`

```sh
Ivebe\Lffmpeg\LffmpegServiceProvider::class
```

Publishing at least config from the vendor is required, this is tagged as defaults. Available tags are `migrations`, `models`, `assets`, `views` and `defaults`. If you are building from scratch you can just ommit tag completely and publish everything. However if you already have system in place, and want to adopt it to use Lffmpeg, then publish defaults (config) only, and add current models implementation in the config file.

```sh
php artisan vendor:publish --tag=defaults
```

### Config

This is where encoding settings are placed, and also models that will handle the logic. Lffmpeg already have 3 eloquent models for **Video**, **Thumb**, and **EncodingProgress**

##### Paths
Paths should not end with slash.

* ***paths#tmp_path*** path on disk to the temporary directory. This is where you should upload your video file.
* **paths#content_path** path on disk where encoded videos and thumbs will be placed 

##### FFmpeg
Location of the ffmpeg binaries.

* ***encoding#ffmpeg_binaries*** absolute path to the ffmpeg binary
* **encoding#ffprobe_binaries** absolute path to the ffprobe binary
* **encoding#threads** number of threads to use for encoding

##### Encoding
Desired profile used for encoding. Lffmpeg will automatically detect best possibile quality and encode from it. In other words if you have video that is not *HD*, then *1080p* profile wont be encoded, it will start from the best match profile. There is no upscalling.

* ***encoding*** array of encoding profiles. Example:
```php
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
    ]
```

##### Video model
Videos are manipulated using VideoRepository. By default there is only Eloquent repository, so you need to set video model in the config file. If you are already having your video model, then just point proper column names and your model will be used instead. Note that variables are marked with `#` while colum names are marked with `@`.

* **video#model** Class that represent video model. (Ex. *\App\Video::class*)
* **video@duration** Name of the duration column
* **video@status** Name of the status column
* **video@default_thumb** Name of the default_thumb column

##### Thumb model
Thumbs are manipulated using ThumbRepository. By default there is only Eloquent repository, so you need to set thumb model in the config file. If you are already having your thumb model, then just point proper column names and your model will be used instead. Note that variables are marked with `#` while colum names are marked with `@`.

* **thumb#model** Class that represent thumb model. (Ex. *\App\Thumb::class*)
* **thumb#interval** Number of seconds between which thumbs are taken.
* **thumb#name-format** sprintf string where only parameter is number of image (Ex. thumb_%s.jpg)
* **thumb#width** desired width of a thumb
* **thumb#height** desired height of a thumb
* **thumb@video_id** name of foreign key video_id
* **thumb@name** column name
* **thumb@scene_time** column scene_time

##### EncodingProgress model
EncodingProgress doesn't have it's own repository, it is manipulated using VideoRepository. By default there is only Eloquent repository, so you need to set EncodingProgress model in the config file. If you are already having your EncodingProgress model, then just point proper column names and your model will be used instead. Note that variables are marked with `#` while colum names are marked with `@`.

* **progress#model** Class that represent EncodingProgress model (Ex. \App\EncodingProgress::class)
* **progress@video_id** name of foreign key video_id
* **progress@quality** name of the quality column
* **progress@status** name of teh status column
* **progress@percent** name of the percent column
* 
### Usage

Basic usage is as simple as typing `Helper::runEncoding(123);` where 123 is video ID in the Video repository. However you should know that each encoding process is done using jobs and queues, so you should setup your queue properly.

### Publishing vendor files
Available tags are `migrations`, `models`, `assets`, `views` and `defaults`.

* **migrations** if you wish to use pre-made models, publish migrations and run `php artisan migrate`
* **models** running `php artisan vendor:publish --tag=models` will place *Video*, *Thumb* and *EncodingProgress* models in the app directory.
* **assets** this will publish js file to the `public/assets/lffmpeg.js` which will have simple js written to handle display of the progress bar. You can see sample of it's usage if you publish views also.
* **views** publish example view file to the `resources/views/lffmpeg.blade.php`
* **defaults** publish config file.
