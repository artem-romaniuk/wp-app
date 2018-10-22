<?php

namespace App\Core\Application;

use App\Core\Container\Container;
use App\Providers\AppServiceProvider;
use App\Providers\ModelServiceProvider;
use App\Providers\WidgetServiceProvider;

class Application
{
    public $container;

    protected $basePath;

    protected $instances;


    public function __construct($basePath = null)
    {
        if ($basePath)
        {
            $this->setBasePath($basePath);
        }

        $this->loadConfigurationFiles();
        $this->loadFunctionFiles();

        Container::setInstance((array) $this->instances);
        $this->container = Container::getInstance();

        $this->registerBaseServiceProviders();
    }

    protected function registerBaseServiceProviders()
    {
        $this->container->register(new AppServiceProvider());
        $this->container->register(new ModelServiceProvider());
        $this->container->register(new WidgetServiceProvider());
    }

    protected function loadConfigurationFiles()
    {
        $files = $this->getFiles($this->configPath());

        foreach ($files as $key => $path)
        {
            $this->instance('config.' . $key, require $path);
        }
    }

    protected function loadFunctionFiles()
    {
        $files = $this->getFiles($this->functionsPath());

        foreach ($files as $path)
        {
            require $path;
        }
    }

    protected function getFiles($directory)
    {
        $files = [];

        $dir = realpath($directory);

        if (is_dir($dir))
        {
            foreach (glob($dir . DIRECTORY_SEPARATOR . '*.php') as $file)
            {
                $files[basename($file, '.php')] = $file;
            }
        }

        return $files;
    }

    public function setBasePath($basePath = null)
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();

        return $this;
    }

    protected function bindPathsInContainer()
    {
        $this->instance('path.base', $this->basePath());
        $this->instance('path.app', $this->appPath());
        $this->instance('path.config', $this->configPath());
        $this->instance('path.functions', $this->functionsPath());
        $this->instance('path.views', $this->viewsPath());
    }

    public function basePath($path = '')
    {
        return $this->basePath . ($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public function appPath($path = '')
    {
        return $this->basePath. DIRECTORY_SEPARATOR . 'app' . ($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public function configPath($path = '')
    {
        return $this->appPath() . DIRECTORY_SEPARATOR . 'config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function functionsPath($path = '')
    {
        return $this->appPath() . DIRECTORY_SEPARATOR . 'functions' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public function viewsPath($path = '')
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'views' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    protected function instance($key, $value)
    {
        $this->instances[$key] = $value;
    }

    public function make($class, array $parametrs = [])
    {
        return new $class();
    }
}