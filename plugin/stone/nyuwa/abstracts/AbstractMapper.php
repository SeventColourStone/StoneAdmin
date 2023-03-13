<?php


namespace plugin\stone\nyuwa\abstracts;


use plugin\stone\nyuwa\NyuwaModel;
use plugin\stone\nyuwa\traits\MapperTrait;

abstract class AbstractMapper
{
    use MapperTrait;

    /**
     * @var NyuwaModel
     */
    public $model;

    abstract public function assignModel();


    public function __construct()
    {
        $this->assignModel();
    }

}
