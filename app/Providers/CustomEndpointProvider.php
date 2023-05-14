<?php

namespace WordCamp\App\Providers;

use WordCamp\Bootstrap\Contracts\ServiceProviderContract;

class CustomEndpointProvider implements ServiceProviderContract
{
    private string $endpointName = 'print-wordcamp-id-cards';

    public function boot(): void
    {
        add_action('init', [$this, 'endpoint']);
        add_action('template_include', [$this, 'endpointTemplate']);

        add_action('wp_loaded', function () {
            flush_rewrite_rules();
        });

        add_filter('query_vars', function ($queryVars) {
            $queryVars[] = $this->endpointName;
            return $queryVars;
        });
    }

    function endpoint()
    {
        add_rewrite_endpoint($this->endpointName, EP_ROOT | EP_PAGES);
    }

    function endpointTemplate($template)
    {
        global $wp_query;

        if (isset($wp_query->query_vars[$this->endpointName])) {
            (wep_view('print-id-cards.php'));
            exit;
        }

        return $template;
    }

}