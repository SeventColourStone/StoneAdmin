<?php
/**

 */

declare(strict_types=1);
namespace nyuwa\generator;

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
     * NyuwaGenerator constructor.
     */
    public function __construct()
    {
        $this->setStubDir(BASE_PATH . '/nyuwa/generator/stubs/');
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
}