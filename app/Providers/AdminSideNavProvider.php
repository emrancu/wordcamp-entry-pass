<?php

namespace WordCamp\App\Providers;

use WordCamp\App\Controllers\SideNavController;
use WordCamp\App\Enums\AdminSlug;
use WordCamp\Bootstrap\Contracts\ServiceProviderContract;

class AdminSideNavProvider implements ServiceProviderContract
{
    public function boot(): void
    {
        add_menu_page(
            "WordCamp",
            "WordCamp",
            'manage_options',
            AdminSlug::MAIN->value,
            [new SideNavController(), 'index'],
              '',
             100
        );
    }
}