<?php


namespace plugin\stone\nyuwa\office;


use plugin\stone\nyuwa\NyuwaModel;
use plugin\stone\nyuwa\NyuwaResponse;

interface ExcelPropertyInterface
{
    public function import(NyuwaModel $model, ?\Closure $closure = null): bool;

    public function export(string $filename, array|\Closure $closure);
}