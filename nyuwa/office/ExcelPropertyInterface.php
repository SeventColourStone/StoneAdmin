<?php


namespace nyuwa\office;


use nyuwa\NyuwaModel;
use nyuwa\NyuwaResponse;

interface ExcelPropertyInterface
{
    public function import(NyuwaModel $model, ?\Closure $closure = null): bool;

    public function export(string $filename, array|\Closure $closure);
}