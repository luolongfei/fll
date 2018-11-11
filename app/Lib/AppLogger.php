<?php

namespace App\Lib;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AppLogger
{

    private $loggerHandles = [];
    private $cliModel = false;
    private $logType = 'runtime';
    private static $instance = null;

    private function __construct()
    {
        $this->cliModel = php_sapi_name() == 'cli' ? true : false;
    }

    protected function getLogger()
    {
        $logType = $this->logType;
        if (!isset($this->loggerHandles[$logType])) {
            $this->loggerHandles[$logType] = new Logger($logType, [$this->getMonologHandler($logType)]);
        }

        return $this->loggerHandles[$logType];
    }

    protected function write($funcName, $msg, $context = [])
    {
        $logger = $this->getLogger($this->logType);
        $logger->{$funcName}($msg, $context);
    }

    protected function getMonologHandler($logType)
    {
        $logpath = storage_path('logs/' . ($this->cliModel ? 'cli' : 'web') . '/' . date('Ymd') . '/' . $this->logType . '.log');
        return (new StreamHandler($logpath, Logger::DEBUG))
            ->setFormatter(new LineFormatter(null, null, null, true));
    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setLogType($logType)
    {
        $this->logType = $logType;
        return $this;
    }

    public function __call($funcName, $arguments)
    {
        $msg = $arguments[0];
        $context = isset($arguments[1]) ? $arguments[1] : [];
        $this->write($funcName, $msg, $context);
    }

}