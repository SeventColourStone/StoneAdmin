<?php


namespace nyuwa\abstracts;


use nyuwa\NyuwaModel;
use nyuwa\traits\MapperTrait;

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
