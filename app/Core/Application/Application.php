<?php

namespace App\Core\Application;


class Application
{
    protected $basePath;

    protected $config = [];


    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->loadConfigurationFiles();

        $this->loadFunctionFiles();
    }


    protected function loadConfigurationFiles()
    {
        $files = $this->getFiles($this->configPath());

        foreach ($files as $key => $path) {
            $this->setConfig($key, require $path);
        }
    }


    protected function loadFunctionFiles()
    {
        $files = $this->getFiles($this->functionsPath());

        foreach ($files as $path) {
            require $path;
        }
    }


    protected function getFiles($directory)
    {
        $files = [];

        $dir = realpath($directory);

        if (is_dir($dir)) {
            foreach (glob($dir . DIRECTORY_SEPARATOR . '*.php') as $file) {
                $files[basename($file, '.php')] = $file;
            }
        }

        return $files;
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


    public function functionsPath($path = '')
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'functions' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }


    public function setConfig($key, $value = null)
    {
        $this->config[$key] = $value;
    }


    public function getConfig($key = null, $default = null)
    {
        return $key && isset($this->config[$key]) ? $this->config[$key] : ($key ? $default : $this->config);
    }
}