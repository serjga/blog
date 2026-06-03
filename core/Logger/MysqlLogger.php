<?php

namespace App\Logger;

trait MysqlLogger
{
    protected string $_logDirectory = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR. '..' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs';

    public function queryLog(string $sql, array $params = []): void
    {
        if (!is_dir($this->_logDirectory)) {
            mkdir($this->_logDirectory, 0755, true);
        }

        $logMessage = sprintf("[%s] \n%s\n %s", date('Y-m-d H:i:s'), $sql, print_r($params, true));

        error_log($logMessage, 3, $this->_logDirectory . DIRECTORY_SEPARATOR .'/db.log');
    }
}
