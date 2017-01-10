<?php

namespace Ivebe\Lffmpeg\Config;

use \Illuminate\Support\Facades\Config as LaravelConfig;

/**
 * Class Config
 *
 * Wrapper in case that Laravel decide to modify Config class in the future
 *
 * @package Ivebe\Lffmpeg\Config
 */
class Config extends LaravelConfig
{

    public static function get($key)
    {
        $default = include __DIR__ . DIRECTORY_SEPARATOR . 'lffmpeg.php';
        $defaultValue = isset($default[$key]) ? $default[$key] : null;

        return parent::get('lffmpeg.' . $key, $defaultValue);
    }

}