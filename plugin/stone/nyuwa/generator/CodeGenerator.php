<?php
/**

 */

declare(strict_types=1);
namespace plugin\stone\nyuwa\generator;

interface CodeGenerator
{
    public function generator($genFilePath);

    public function preview();
}