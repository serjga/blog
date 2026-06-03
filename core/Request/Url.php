<?php

namespace App\Request;

class Url
{
    protected Request $_request;
    function __construct ()
    {
        $this->_request = (new RequestFactory())->create();
    }

    public function getUrl(array $params): string
    {
        $url = '';
        if (isset($params['path'])) {
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . $params['path'];
            if (count($params) > 1) {
                unset($params['path']);
                $url .= '?' . http_build_query($params);
            }
        }
        return $url;
    }

    public function getCurrentUrl(array $rewriteParams): string
    {
        $getParams = $this->_request->get();
        if (count($rewriteParams) > 0) {
            $getParams = array_replace($getParams, $rewriteParams);
        }
        return $this->getServerUrl() . strtok($_SERVER["REQUEST_URI"], '?') .'?' . http_build_query($getParams);
    }

    public function getImageUrl(array $params): string
    {
        if (isset($params['path'])) {

            if (!empty($params['path']) && file_exists(__DIR__ . "/../../storage/media/images/" . $params['path'])) {
                $mediaUrl = $this->getServerUrl() . "/media/images/" . $params['path'];
                return $mediaUrl;
            } else {
                return $this->getServerUrl() . "/media/images/placeholder.jpg";
            }
        } else {
            return '';
        }
    }

    public function getServerUrl(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    }
}
