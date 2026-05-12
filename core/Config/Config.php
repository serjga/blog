<?php
namespace App\Config;

class Config {
    private array $_config = [];

    function __construct($configGroup) {
        $filePath = __DIR__ . "/../../configs/$configGroup.php";
        if(file_exists($filePath)) {
            eval('$this->_config = require($filePath);');
        }
    }

    public function get(string $configPath = null)
    {
        if ($configPath) {
            $arrConfig = explode('.', $configPath);
            if (is_array($arrConfig)) {
                return $this->_search($arrConfig, $this->_config);
            }
            return null;
        } else {
            return $this->_config;
        }
    }

    private function _search(array $stack, array $arr)
    {
        $searchKey = array_shift($stack);

        foreach($arr as $configKey => $configValue) {
            if($configKey === $searchKey) {
                if (is_array($configValue) && count($stack) > 0) {
                    return $this->_search($stack, $configValue);
                } else {
                    return $configValue;
                }
            }
        }
        return null;
    }
}
