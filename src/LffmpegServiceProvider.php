<?php

namespace Ivebe\Lffmpeg;

use Illuminate\Support\ServiceProvider;
use \Illuminate\Contracts\Events\Dispatcher as IDispatcher;

class LffmpegServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(IDispatcher $events)
    {
        parent::boot($events);

        $events->listen('Ivebe\Lffmpeg\Events\ProcessVideoEvent', 'Ivebe\Lffmpeg\Listeners\ProcessVideoListener');

        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/Routes/lffmpeg.php';
        }

        $this->publishes([
            __DIR__ . '/Publishes/Migrations/2016_01_01_120000_create_videos_table.php' => database_path('migrations/2016_01_01_120000_create_videos_table.php'),
            __DIR__ . '/Publishes/Migrations/2016_01_01_120001_create_thumbs_table.php' => database_path('migrations/2016_01_01_120001_create_thumbs_table.php'),
            __DIR__ . '/Publishes/Migrations/2016_01_01_120002_create_encoding_progress_table.php' => database_path('migrations/2016_01_01_120002_create_encoding_progress_table.php'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/Publishes/Models/Video.php' => app_path('Video.php'),
            __DIR__ . '/Publishes/Models/Thumb.php' => app_path('Thumb.php'),
            __DIR__ . '/Publishes/Models/EncodingProgress.php' => app_path('EncodingProgress.php'),
        ], 'models');

        $this->publishes([
            __DIR__ . '/Publishes/Assets/lffmpeg.js' => public_path('assets/lffmpeg.js'),
        ], 'assets');

        $this->publishes([
            __DIR__ . '/Publishes/Views/index.blade.php' => base_path('resources/views/lffmpeg.blade.php'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/Config/lffmpeg.php' => config_path('lffmpeg.php'),
        ], 'defaults');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind( Services\Contracts\IVideoService::class, Services\VideoService::class );
        $this->app->bind( Libs\Contracts\IEncodingLib::class, Libs\EncodingLib::class);
        $this->app->bind( Libs\Contracts\IImageLib::class, Libs\ImageLib::class);

        $this->app->bind( \Psr\Log\LoggerInterface::class, \Illuminate\Log\Writer::class );

        $this->app->bind( Repositories\Contracts\IVideoRepository::class, Repositories\VideoRepository::class );
        $this->app->bind( Repositories\Contracts\IThumbRepository::class, Repositories\ThumbRepository::class );

        $this->app->bind( Events\Contracts\IProcessVideoEvent::class, Events\ProcessVideoEvent::class );
        $this->app->bind( Factories\Contracts\IJobsFactory::class, Factories\JobsFactory::class );
    }
}
