<?php

namespace WordCamp\App\Supports;

use WordCamp\Bootstrap\Contracts\ConfigContract;

class Config extends ConfigContract
{
    protected static Config|null $instance = null;

    public static function get($key): mixed
    {
        return static::getInstance()->resolveData($key);
    }

    private static function getInstance(): Config
    {
        if (!self::$instance) {
            self::$instance = new Config();
        }

        return self::$instance;
    }

}