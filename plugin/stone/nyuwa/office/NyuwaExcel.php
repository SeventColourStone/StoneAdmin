<?php


namespace plugin\stone\nyuwa\office;


use Doctrine\Common\Annotations\AnnotationReader;
use plugin\stone\nyuwa\annotation\ExcelData;
use plugin\stone\nyuwa\annotation\ExcelProperty;
use plugin\stone\nyuwa\exception\NyuwaException;
use plugin\stone\nyuwa\interfaces\NyuwaModelExcel;
use plugin\stone\nyuwa\NyuwaModel;
use plugin\stone\nyuwa\NyuwaResponse;
use ReflectionClass;
use ReflectionMethod;

abstract class NyuwaExcel
{

    public const ANNOTATION_NAME = 'nyuwa\annotation\ExcelProperty';

    /**
     * @var array|null
     */
    protected ?array $annotationMate;

    /**
     * @var array
     */
    protected array $property = [];


    /**
     * @param String $dto
     * @param NyuwaModel $model
     */
    public function __construct(String $dto)
    {
        if (! (new $dto) instanceof NyuwaModelExcel) {
            throw new NyuwaException('dto does not implement an interface of the NyuwaModelExcel', 500);
        }
        try {

            $class = new ReflectionClass($dto);
            $class_name = $class->name;
            var_dump("class_name:".$class_name);
    //        $methods = $class->getAttributes(ReflectionMethod::IS_PUBLIC);
            $properties = $class->getProperties(ReflectionMethod::IS_PUBLIC);
            $reader = new AnnotationReader();
            /** @var  $class_annos *注解的读取类 */
            $class_annos = $reader->getClassAnnotation($class,ExcelData::class);

            $array = [];
            foreach ($properties as $item) {
                $excelPropertyName = $item->getName();

                var_dump($excelPropertyName);
                $propertyAnnotation = $reader->getPropertyAnnotations($item);//, ExcelProperty::class
                $array[$excelPropertyName]= $propertyAnnotation[0];
                $this->property[ $array[$excelPropertyName]->index ] = [
                    'name'  => $excelPropertyName,
                    'value' => $array[$excelPropertyName]->value,
                    'width' => $array[$excelPropertyName]->width ?? null,
                    'align' => $array[$excelPropertyName]->align ?? null,
                    'headColor' => $array[$excelPropertyName]->headColor ?? null,
                    'headBgColor' => $array[$excelPropertyName]->headBgColor ?? null,
                    'color' => $array[$excelPropertyName]->color ?? null,
                    'bgColor' => $array[$excelPropertyName]->bgColor ?? null,
                ];
            }
            $this->annotationMate = $array;

            ksort($this->property);
            var_dump($this->property);
//            var_dump($this->annotationMate);
            var_dump(json_encode($this->annotationMate,JSON_UNESCAPED_UNICODE));
        }catch (\Exception $e){
            var_dump("异常了：".$e->getMessage());
            throw new NyuwaException('dto annotation info ：'.$e->getMessage(), 500);
        }
    }

    /**
     * @return array
     */
    public function getProperty(): array
    {
        return $this->property;
    }


    /**
     * 下载excel
     * @param string $filename
     * @param string $content
     */
    protected function downloadExcel(string $filename, string $content)
    {
        return (new NyuwaResponse())
            ->withHeader('Server', 'NyuwaAdmin')
            ->withHeader('content-description', 'File Transfer')
            ->withHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->withHeader('content-disposition', "attachment; filename=".rawurlencode($filename))
            ->withHeader('content-transfer-encoding', 'binary')
            ->withHeader('pragma', 'public')
            ->withBody($content);
    }
}