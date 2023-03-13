<?php


namespace plugin\stone\nyuwa\event;


class BizInfoEvent
{

    const TYPE_LAZADA_ORDER = "lazada-order";

    private $keySn;
    private $content;

    private $type;

    private $platformId;

    /**
     * BizInfoEvent constructor.
     * @param $keySn
     * @param $content
     * @param $type
     * @param $platformId
     */
    public function __construct($keySn, $content, $type, $platformId)
    {
        $this->keySn = $keySn;
        $this->content = $content;
        $this->type = $type;
        $this->platformId = $platformId;
    }

    /**
     * @return mixed
     */
    public function getKeySn()
    {
        return $this->keySn;
    }

    /**
     * @param mixed $keySn
     */
    public function setKeySn($keySn): void
    {
        $this->keySn = $keySn;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getPlatformId()
    {
        return $this->platformId;
    }

    /**
     * @param mixed $platformId
     */
    public function setPlatformId($platformId): void
    {
        $this->platformId = $platformId;
    }




}