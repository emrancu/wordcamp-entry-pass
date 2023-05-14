<?php


use WordCamp\Bootstrap\Migrations\AttendeeEventTableMigration;
use WordCamp\Bootstrap\Migrations\AttendeeTableMigration;
use WordCamp\Bootstrap\Migrations\EventTableMigration;

return [

    /**
     * application/plugin name
     *
     * @type string
     */
    'name' => 'WordCamp Entry Pass',

    /**
     * application/plugin slug/text domain
     *
     * @type string
     */
    'slug' => 'wordcamp-entry-pass',

    /**
     * application/plugin version
     *
     * @type string
     */
    'version' => '1.0.0',

    /**
     * rest api namespace
     *
     * @type string
     */
    'api_namespace' => 'wordcamp-entry-pass/v1',

    /**
     * add your custom Service Providers here
     * service provider path App/Providers
     *
     * @type array
     */
    'providers' => [
        WordCamp\App\Providers\AdminSideNavProvider::class,
        WordCamp\App\Providers\CustomEndpointProvider::class,
        WordCamp\App\Providers\AdminEnqueueProvider::class,
        WordCamp\App\Providers\RestApiProvider::class,
    ],

    /**
     * add your rest api middleware here
     * middleware path App/Middleware
     *
     * @type array
     */
    'middleware' => [
    ],

    'product_id' => 1,

    'license_api' => 'https://uihut.net/connect-domain-form-wp',

    'migrations' => [
        AttendeeTableMigration::class,
        EventTableMigration::class,
        AttendeeEventTableMigration::class,
    ],
];