<?php

use WordCamp\Bootstrap\Application;

if (!function_exists('wep_base_path')) {
    function wep_base_path($path): string
    {
        return Application::getInstance()->getBasePath($path);
    }
}

if (!function_exists('wep_base_url')) {
    function wep_base_url($path): string
    {
        return Application::getInstance()->getBaseUrl($path);
    }
}

if (!function_exists('wep_view')) {
    function wep_view($path, array $data = []): string
    {
        if (count($data)) {
            extract($data);
        }

        return include wep_base_path('resources/views'.DIRECTORY_SEPARATOR.$path);
    }
}