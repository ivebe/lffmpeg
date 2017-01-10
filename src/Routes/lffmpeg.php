<?php

Route::group(array('prefix' => 'api/lffmpeg/v1'), function () {
    Route::get('video/progress', '\Ivebe\Lffmpeg\Api\v1\VideoController@progressAll');
});
