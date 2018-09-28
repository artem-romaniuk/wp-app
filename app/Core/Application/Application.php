<?php

namespace App\Core\Application;

use App\Core\FileSystem\FileSystem;

class Application
{
    protected $basePath;

    protected $config = [];

    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->resolve();
    }

    protected function resolve()
    {
        $this->resolveConfig();
    }


    protected function resolveConfig()
    {
        $fileSystem = new FileSystem();

        $configPath = $this->configPath();

        $files = $fileSystem->files($configPath);

        foreach ($files as $file) {
            $this->config[$fileSystem->name($file)] = $fileSystem->getRequire($file);
        }
    }


    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        return $this;
    }


    public function configPath($path = '')
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }


    public function getConfig($key, $default)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }
}