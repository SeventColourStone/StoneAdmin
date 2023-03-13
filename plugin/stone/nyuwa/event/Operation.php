<?php

declare(strict_types = 1);
namespace plugin\stone\nyuwa\event;

class Operation
{
    /**
     * @var array
     */
    protected $requestInfo;


    public function __construct(array $requestInfo)
    {
        $this->requestInfo = $requestInfo;
    }

    public function getRequestInfo(): array
    {
        return $this->requestInfo;
    }
}