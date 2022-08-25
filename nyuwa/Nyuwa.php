<?php


namespace nyuwa;


use Psr\Container\ContainerInterface;
use support\Container;
use Symfony\Component\Filesystem\Filesystem;

class Nyuwa
{
    /**
     * @var string
     */
    private static $version = '0.0.1';

    /**
     * @var string
     */
    private $appPath = '';

    /**
     * @var array
     */
    private $moduleInfo = [];

    /**
     */
    public function __construct()
    {
        $this->setAppPath(app_path());
        $this->scanModule();
    }

    /**
     * @return string
     */
    public static function getRootPath(): string
    {
        $directory = app_path();
        while(strpos($directory, 'runtime') > 0) {
            $directory = dirname($directory);
        }
        return $directory . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function app(string $id)
    {
        return Container::get($id);
    }


    /**
     */
    public function scanModule(): void
    {
        $modules = glob(self::getAppPath() . '*');
        $infos = null;
        foreach ($modules as &$mod) if (is_dir($mod)) {
            $modInfo = $mod . DIRECTORY_SEPARATOR . 'config.json';
            if (file_exists($modInfo)) {
                $infos[basename($mod)] = json_decode(file_get_contents($modInfo), true);
            }
        }
        $this->setModuleInfo($infos);
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        return self::$version;
    }

    /**
     * @return mixed
     */
    public function getAppPath(): string
    {
        return $this->appPath . DIRECTORY_SEPARATOR;
    }

    /**
     * @param mixed $appPath
     */
    public function setAppPath(string $appPath): void
    {
        $this->appPath = $appPath;
    }

    /**
     * @param string|null $name
     * @return mixed
     */
    public function getModuleInfo(string $name = null): array
    {
        if (empty($name)) {
            return $this->moduleInfo;
        }
        return $this->moduleInfo[$name] ?? [];
    }

    /**
     * @param mixed $moduleInfo
     */
    public function setModuleInfo($moduleInfo): void
    {
        $this->moduleInfo = $moduleInfo;
    }

    /**
     * @param String $key
     * @param $value
     * @param false $save
     * @return bool
     * @noinspection PhpUnused
     */
    public function setModuleConfigValue(String $key, $value, bool $save = false): bool
    {
        if (strpos($key, '.') > 0) {
            list($mod, $name) = explode('.', $key);
            if (isset($this->moduleInfo[$mod]) && isset($this->moduleInfo[$mod][$name])) {
                $this->moduleInfo[$mod][$name] = $value;
                $save && $this->saveModuleConfig($mod);
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * @param string $mod
     */
    protected function saveModuleConfig(string $mod): void
    {
        if (!empty($mod)) {
            $fs = $this->app(Filesystem::class);
            $modJson = $this->getAppPath() . $mod . DIRECTORY_SEPARATOR . 'config.json';
            if (! $fs->isWritable($modJson)) {
                $fs->chmod($modJson, 666);
            }
            $fs->put($modJson, \json_encode($this->getModuleInfo($mod), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }
    }
}
