<?php
namespace App\View\Template\Smarty;

use App\Config\Config;
use App\View\Template\TemplateCompilerInterface;
use Smarty\Smarty;

class TemplateCompiler implements TemplateCompilerInterface
{
    protected string $_templateExtension = '.tpl';
    protected Smarty $_smarty;
    protected string $_templatesDirPath = __DIR__ . '/templates';
    protected string $_compiledDirectory = '/templates_c';
    protected string $_compiledDirPath;
    protected string $_cacheDirectory = '/cache';
    protected string $_cacheDirPath;
    protected string $_configsDirPath;
    protected ?string $_cacheId;
    protected ?string $_compileId;

    function __construct(array $config = [])
    {
        $appConfig = new Config('app');
        $cacheDirectory = $appConfig->get('smarty_cache_directory');
        $configsDirectory = $appConfig->get('smarty_configs_directory');

        $this->_configsDirPath = $configsDirectory;
        $this->_compiledDirPath = $cacheDirectory . $this->_compiledDirectory;
        $this->_cacheDirPath = $cacheDirectory . $this->_cacheDirectory;

        $this->_smarty = new Smarty();
        $this->_smarty->setCompileDir($this->_compiledDirPath);
        $this->_smarty->setCacheDir($this->_cacheDirPath);
        $this->_smarty->setConfigDir($this->_configsDirPath);
        $this->_smarty->setCaching(\Smarty\Smarty::CACHING_OFF);
        $this->_smarty->configLoad('app.conf');
        $this->_smarty->assign('config', $config);
    }

    private static ?TemplateCompiler $_instance = null;
    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function display(string $templatePath): void
    {
        $this->_smarty->display($this->getTemplateName($templatePath), $this->_cacheId ?? null, $this->_compileId ?? null);
    }

    public function fetch(string $templatePath): string
    {
        return $this->_smarty->fetch($this->getTemplateName($templatePath), $this->_cacheId ?? null, $this->_compileId ?? null);
    }

    public function getTemplateName(string $templatePath): string
    {
        return $templatePath . $this->_templateExtension;
    }

    public function setTemplatesDir(string $dir): self
    {
        $this->_templatesDirPath = $dir;
        $this->_smarty->setTemplateDir($this->_templatesDirPath);
        return $this;
    }

    public function getCacheDirectory(): string
    {
        return $this->_cacheDirPath;
    }

    public function addTemplateVariable(string $variableName, $value): self
    {
        $this->_smarty->assign($variableName, $value);
        return $this;
    }

    public function registerObject(string $key, object $obj): self
    {
        $this->_smarty->registerObject($key, $obj);
        return $this;
    }

    public function needCache(?int $expiration = null): self
    {
        if (is_null($expiration)) {
            $this->_smarty->setCaching(\Smarty\Smarty::CACHING_OFF);
        } else if ($expiration > 0) {
            $this->_smarty->setCaching(\Smarty\Smarty::CACHING_LIFETIME_SAVED);
            $this->_smarty->setCacheLifetime($expiration);
        } else {
            // default cache expiration 3600 sec (1 hour)
            $this->_smarty->setCaching(\Smarty\Smarty::CACHING_LIFETIME_CURRENT);
        }
        return $this;
    }

    public function setCacheId(?string $cacheId): self
    {
        $this->_cacheId = $cacheId;
        return $this;
    }

    public function setCompileId(?string $compileId): self
    {
        $this->_compileId = $compileId;
        return $this;
    }

    public function hasCache(?string $templatePath = null, ?string $cacheId = null, ?string $compileId = null): bool
    {
        return $this->_smarty->isCached($this->getTemplateName($templatePath), $cacheId, $compileId);
    }

    public function cleanCache(string $cacheId): void
    {
        $this->_smarty->clearCache(null, $cacheId);
    }
    public function cleanAllCache(): void
    {
        $this->_smarty->clearAllCache();
    }

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
