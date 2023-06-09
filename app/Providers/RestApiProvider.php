<?php

namespace WordCamp\App\Providers;

use WordCamp\App\Controllers\RestApiController;
use WordCamp\App\Enums\AdminSlug;
use WordCamp\App\Supports\Config;
use WordCamp\Bootstrap\Contracts\ServiceProviderContract;

class RestApiProvider implements ServiceProviderContract
{
    public function boot(): void
    {
        add_action('rest_api_init', function () {
            register_rest_route(Config::get('app.api_namespace'), '/events', array(
                'methods' => 'GET',
                'callback' => [new RestApiController(), 'events'],
            ));

            register_rest_route(Config::get('app.api_namespace'), '/attendee-event', array(
                'methods' => 'POST',
                'callback' => [new RestApiController(), 'attendeeEvent'],
            ));

            register_rest_route(Config::get('app.api_namespace'), '/attendee/(?P<id>\w+)', array(
                'methods' => 'GET',
                'callback' => [new RestApiController(), 'attendee'],
            ));
        });
    }

    public function enqueue(): void
    {
        wp_enqueue_script('wep-app-script', wep_base_url('public/js/app.js'), [], time(), true);
        wp_enqueue_style('wep-app-style', wep_base_url('public/css/app.css'), [], time(), 'all');
    }
}