<?php
/**

 */

declare(strict_types=1);
namespace plugin\stone\nyuwa\generator;

use Psr\Container\ContainerInterface;

abstract class NyuwaGenerator
{
    /**
     * @var string
     */
    protected string $stubDir;

    /**
     * @var string
     */
    protected string $namespace;


    /**
     * @var string
     */
    protected string $package;


    /**
     * NyuwaGenerator constructor.
     */
    public function __construct()
    {
        $this->setStubDir(BASE_PATH . '/plugin/stone/nyuwa/generator/stubs/');
        $this->setPackage(env("GENCODE_PACKAGE","business"));
    }

    public function getStubDir(): string
    {
        return $this->stubDir;
    }

    public function setStubDir(string $stubDir)
    {
        $this->stubDir = $stubDir;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function replace(): self
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getPackage(): string
    {
        return $this->package;
    }

    /**
     * @param string $package
     */
    public function setPackage(string $package): void
    {
        $this->package = $package;
    }
}