<?php

namespace WordCamp\Bootstrap\Contracts;

abstract class ConfigContract
{
    protected string|null $filePath;
    protected string $fileExtension = '.php';
    private array $data = [];

    public function __construct()
    {
        $this->filePath = wep_base_path('config');
    }

    abstract public static function get($key): mixed;

    protected function resolveData(string $key): mixed
    {
        $key_tree = explode('.', $key);

        $finalData = [];
        $fileFound = false;
        $filePath = '';

        foreach ($key_tree as $key) {
            $filePath .= DIRECTORY_SEPARATOR.$key;

            if (!$fileFound && is_file($this->addExtension($filePath))) {
                $finalData = $this->setData($filePath);
                $fileFound = true;
            } else {
                if ($fileFound) {
                    $finalData = $finalData[$key] ?? '';
                }
            }
        }

        return empty($finalData) ? null : $finalData;
    }

    private function addExtension(string $file): string
    {
        return $this->filePath.$file.$this->fileExtension;
    }

    protected function setData(string $filePath): mixed
    {
        if (!isset($this->data[$filePath])) {
            $this->data = include $this->addExtension($filePath);
        }
        return $this->data;
    }

    /**
     * @param  string  $path
     * @return bool
     */
    protected function isFile(string $path): bool
    {
        return is_file($this->addExtension($path));
    }
}