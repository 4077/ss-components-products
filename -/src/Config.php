<?php namespace ss\components\products;

class Config
{
    public static $instance;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }

    private $config;

    public function __construct()
    {
        $this->config = dataSets()->get('modules/ss-components-products:');
        $envConfig = dataSets()->get('modules/ss-components-products:' . app()->getEnv());

        ra($this->config, $envConfig);
    }

    private $cacheByPath = [];

    public function get($path)
    {
        if (!isset($this->cacheByPath[$path])) {
            $this->cacheByPath[$path] = ap($this->config, $path);
        }

        return $this->cacheByPath[$path];
    }
}
