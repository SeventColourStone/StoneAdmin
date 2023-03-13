<?php


namespace plugin\stone\nyuwa\annotation;


use Attribute;
use plugin\stone\nyuwa\abstracts\AbstractAnnotation;

/**
 * excel导入导出元数据。
 * @Annotation
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class ExcelProperty extends AbstractAnnotation
{
    /**
     * 列表头名称
     * @var string
     * @Required
     */
    public string $value;

    /**
     * 列顺序
     * @var int
     */
    public int $index;

    /**
     * 宽度
     * @var int
     */
    public int $width;

    /**
     * 对齐方式，默认居左
     * @var string
     */
    public string $align;

    /**
     * 列表头字体颜色
     * @var string
     */
    public string $headColor;

    /**
     * 列表头背景颜色
     * @var string
     */
    public string $headBgColor;

    /**
     * 列表体字体颜色
     * @var string
     */
    public string $color;

    /**
     * 列表体背景颜色
     * @var string
     */
    public string $bgColor;

//    public function __construct(...$value)
//    {
//
//        $formattedValue = $this->formatParams($value);
//        $this->value    = $formattedValue["value"];
//        if (isset($formattedValue['index'])) {
//            $this->index    = $formattedValue["index"];
//        }
//        if (isset($formattedValue['width'])) {
//            $this->width    = $formattedValue["width"];
//        }
//        if (isset($formattedValue['align'])) {
//            $this->align    = $formattedValue["align"];
//        }
//        if (isset($formattedValue['headColor'])) {
//            $this->headColor    = $formattedValue["headColor"];
//        }
//        if (isset($formattedValue['headBgColor'])) {
//            $this->headBgColor    = $formattedValue["headBgColor"];
//        }
//        if (isset($formattedValue['color'])) {
//            $this->color    = $formattedValue["color"];
//        }
//        if (isset($formattedValue['bgColor'])) {
//            $this->bgColor    = $formattedValue["bgColor"];
//        }
//    }
}