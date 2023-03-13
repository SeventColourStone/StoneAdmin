<?php


namespace plugin\stone\nyuwa\annotation;


use Attribute;
use plugin\stone\nyuwa\abstracts\AbstractAnnotation;

/**
 * excel导入导出元数据。
 * @Annotation
 * @Target({"ALL"})
 */
class ExcelData extends AbstractAnnotation{

    /**
     * 列表头名称
     * @var string
     */
//    public string $key = "";
//
//    public function __construct(...$value)
//    {
//        $this->bindMainProperty('key', $value);
//        $this->key = $value;
//    }

}