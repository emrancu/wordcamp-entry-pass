<?php

namespace WordCamp\Bootstrap;

use WordCamp\App\Supports\Config;
use WordCamp\Bootstrap\Contracts\MigrationContact;
use WordCamp\Bootstrap\Contracts\ServiceProviderContract;

class Application
{

    protected static ?Application $instance = null;
    private string $pluginFile;
    private string $basePath;
    private string $baseUrl;

    /**
     * @throws \ReflectionException
     */
    public function __construct(string $path)
    {
        $this->setBasePath($path);
    }


    public static function getInstance(string $path = null): self
    {
        if (null === self::$instance) {
            self::$instance = new self($path);
        }

        return self::$instance;
    }


    public static function start(): void
    {
        foreach (Config::get('app.providers') ?? [] as $provider) {
            /** @var ServiceProviderContract $providerInstance */
            $providerInstance = new $provider();

            if ($providerInstance instanceof ServiceProviderContract) {
                $providerInstance->boot();
            }
        }
    }

    public function active(): void
    {
        foreach (Config::get('app.migrations') ?? [] as $migration) {
            /** @var ServiceProviderContract $providerInstance */
            $migrationInstance = new $migration();

            if ($migrationInstance instanceof MigrationContact) {
                $migrationInstance->execute();
            }
        }
    }

    public function deactivate(): void {}

    /**
     * @return string
     */
    public function getBasePath($path): string
    {
        return $this->basePath.($path ? DIRECTORY_SEPARATOR.$path : '');
    }

    public function getBaseUrl($path = null): string
    {
        return trailingslashit(plugins_url('/', $this->pluginFile)) . ($path ? '/' . $path : $path);
    }

    public function setBasePath(string $path): void
    {
        $this->pluginFile = $path;
        $this->basePath = untrailingslashit(plugin_dir_path($path));
        $this->baseUrl = trailingslashit(plugins_url('/', $this->pluginFile)) ;
    }

}