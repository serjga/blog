<?php
namespace App\Response;

class Response
{
    public function success($data = []): void
    {
        $result = (empty($data)) ? ['success' => 'ok'] : $data;

        print json_encode($result);
    }
}
