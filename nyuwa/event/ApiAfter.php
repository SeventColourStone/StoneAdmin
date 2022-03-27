<?php

declare(strict_types = 1);
namespace nyuwa\event;

use support\Log;
class ApiAfter
{
    protected $apiData;

    protected $result;

    public function __construct(?array $apiData, ResponseInterface
    $result)
    {
        $this->apiData = $apiData;
        $this->result = $result;
    }

    public function getApiData(): ?array
    {
        return $this->apiData;
    }

    public function getResult(): ResponseInterface
    {
        return $this->result;
    }
}