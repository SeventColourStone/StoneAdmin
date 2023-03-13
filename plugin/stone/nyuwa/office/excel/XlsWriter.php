<?php


namespace plugin\stone\nyuwa\office\excel;


use plugin\stone\nyuwa\exception\NyuwaException;
use plugin\stone\nyuwa\NyuwaModel;
use plugin\stone\nyuwa\NyuwaResponse;
use plugin\stone\nyuwa\office\ExcelPropertyInterface;
use plugin\stone\nyuwa\office\NyuwaExcel;
use Vtiful\Kernel\Format;

class XlsWriter extends NyuwaExcel implements ExcelPropertyInterface
{

    public function import(NyuwaModel $model, ?\Closure $closure = null): bool
    {
        $request = request();
        $file = $request->file('file');
        if ($file && $file->isFile()) {
            $tempFileName = 'import_'.time().'.'.$file->getExtension();
            $tempFilePath = BASE_PATH.'/runtime/'.$tempFileName;
            file_put_contents($tempFilePath, file_get_contents($file->getRealPath()));
            $xlsxObject = new \Vtiful\Kernel\Excel(['path' => BASE_PATH . '/runtime/']);
            $data = $xlsxObject->openFile($tempFileName)->openSheet()->getSheetData();
            unset($data[0]);

            $importData = [];
            foreach ($data as $item) {
                $tmp = [];
                foreach ($item as $key => $value) {
                    $tmp[$this->property[$key]['name']] = (string) $value;
                }
                $importData[] = $tmp;
            }

            if ($closure instanceof \Closure) {
                return $closure($model, $importData);
            }

            try {
                foreach ($importData as $item) {
                    $model::create($item);
                }
                @unlink($tempFilePath);
            } catch (\Exception $e) {
                @unlink($tempFilePath);
                throw new \Exception($e);
            }
            return true;
        } else {
            return false;
        }
    }

    public function export(string $filename, array|\Closure $closure): NyuwaResponse
    {
        $filename .= '.xlsx';
        is_array($closure) ? $data = &$closure : $data = $closure();

        $aligns = [
            'left' => Format::FORMAT_ALIGN_LEFT,
            'center' => Format::FORMAT_ALIGN_CENTER,
            'right' => Format::FORMAT_ALIGN_RIGHT,
        ];

        $columnName  = [];
        $columnField = [];
        foreach ($this->property as $item) {
            $columnName[]  = $item['value'];
            $columnField[] = $item['name'];
        }

        $tempFileName = 'export_' . time() . '.xlsx';
        $xlsxObject = new \Vtiful\Kernel\Excel(['path' => BASE_PATH . '/runtime/']);
        $fileObject = $xlsxObject->fileName($tempFileName)->header($columnName);
        $columnFormat = new Format($fileObject->getHandle());
        $rowFormat = new Format($fileObject->getHandle());

        $index = 0;
        for ($i = 65; $i < (65 + count($columnField)); $i++) {
            $columnNumber = chr($i) . '1';
            $fileObject->setColumn(
                sprintf('%s:%s', $columnNumber, $columnNumber),
                $this->property[$index]['width'] ?? mb_strlen($columnName[$index]) * 5,
                $columnFormat->align($this->property[$index]['align'] ? $aligns[$this->property[$index]['align']] : $aligns['left'])
                    ->background($this->property[$index]['bgColor'] ?? Format::COLOR_WHITE)
                    ->border(Format::BORDER_THIN)
                    ->fontColor($this->property[$index]['color'] ?? Format::COLOR_BLACK)
                    ->toResource()
            );
            $index++;
        }

        // 表头加样式
        $fileObject->setRow(
            sprintf('A1:%s1', chr(65 + count($columnField))), 20,
            $rowFormat->bold()->align(Format::FORMAT_ALIGN_CENTER, Format::FORMAT_ALIGN_VERTICAL_CENTER)
                ->background(0x4ac1ff)->fontColor(Format::COLOR_BLACK)
                ->border(Format::BORDER_THIN)
                ->toResource()
        );

        $exportData = [];
        foreach ($data as $item) {
            $yield = [];
            foreach ($this->property as $property) {
                foreach ($item as $name => $value) {
                    if ($property['name'] == $name) {
                        $yield[] = $value;
                        break;
                    }
                }
            }
            $exportData[] = $yield;
        }

        $fileObject->data($exportData);
        $response = response();
        $filePath = $fileObject->output();

        $response->download($filePath, $filename);

        ob_start();
        if ( copy($filePath, 'php://output') === false) {
            throw new NyuwaException('导出数据失败',  500);
        }
        $res = $this->downloadExcel($filename, ob_get_contents());
        ob_end_clean();

        @unlink($filePath);

        return $res;
    }
}