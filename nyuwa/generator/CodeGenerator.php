<?php
/**

 */

declare(strict_types=1);
namespace nyuwa\generator;

interface CodeGenerator
{
    public function generator();

    public function preview();
}