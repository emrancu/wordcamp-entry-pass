<?php

namespace WordCamp\App\Providers;

use WordCamp\App\Enums\AdminSlug;
use WordCamp\App\Supports\Config;
use WordCamp\Bootstrap\Contracts\ServiceProviderContract;

class AdminEnqueueProvider implements ServiceProviderContract
{
    public function boot(): void
    {
        if (isset($_GET['page']) && ($_GET['page'] === AdminSlug::tryFrom($_GET['page'])?->value)) {
            add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        }
    }


    public function enqueue(): void
    {
        wp_enqueue_script('wep-app-script', wep_base_url('public/js/app.js'), [], time(), true);
        wp_enqueue_style('wep-app-style', wep_base_url('public/css/app.css'), [], time(), 'all');

        $slug = Config::get('app.slug');
        $data = [
            "name" => Config::get('app.name'),
            "slug" => $slug,
            "version" => Config::get('app.version'),
            "api_namespace" => Config::get('app.api_namespace'),
            'api_endpoint' => rest_url(Config::get('app.api_namespace')),
            "nonce" => wp_create_nonce("wp_rest")
        ];

        $objectName = str_replace("-", "_", $slug);

        wp_localize_script('jquery-core', $objectName, $data);
    }
}