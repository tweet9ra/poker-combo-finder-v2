<?php


namespace App;


final class Config
{
    private $configFilePath = __DIR__ .'/../config/app.php';

    private $configFileContents;

    private static $instance;

    private function __construct(){}

    private function __clone(){}

    private function __wakeup(){}

    public static function getInstance() : self
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return array
     */
    private function getFile()
    {
        if (!$this->configFileContents) {
            $this->configFileContents = require_once $this->configFilePath;
        }

        return $this->configFileContents;
    }

    /**
     * @param string $paramName
     * @return mixed|null
     */
    public static function get(string $paramName)
    {
        $config = self::getInstance()->getFile();
        return $config[$paramName] ?? null;
    }
}