<?php
namespace App\Response;

class Response
{
    private static ?Response $_instance = null;
    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function success(array $data = []): void
    {
        $result = ['success' => 'ok'];
        if ($data) {
            $result['data'] = $data;
        }

        print json_encode($result);
    }

    public function error(?string $message = null, array $data = []): void
    {
        $errorData = ['error' => 'ok'];
        if ($message) {
            $errorData['message'] = $message;
        }
        if ($data) {
            $errorData['data'] = $data;
        }

        print json_encode($errorData);
    }

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }}
