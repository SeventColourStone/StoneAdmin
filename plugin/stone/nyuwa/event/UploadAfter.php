<?php

namespace plugin\stone\nyuwa\event;

class UploadAfter
{
    public $fileInfo;

    public function __construct(array $fileInfo)
    {
        $this->fileInfo = $fileInfo;
    }
}