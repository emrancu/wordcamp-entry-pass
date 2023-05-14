<?php

namespace WordCamp\Bootstrap;

class Bootstrap
{

    public static function run(string $path): void
    {
        $app = Application::getInstance($path);

        /**
         * register activation hook
         * run only when plugin activate
         */
        register_activation_hook($path, function () use ($app) {
            $app->active();
        });

        /**
         * register de-activation hook
         * run only when plugin de-activate
         */
        register_deactivation_hook($path, function () use ($app) {
            $app->deactivate();
        });

        /**
         * Start application with container and boot service providers
         */
        add_action('plugins_loaded', function () use ($app) {
            $app->start();
        }, 100);
    }

}