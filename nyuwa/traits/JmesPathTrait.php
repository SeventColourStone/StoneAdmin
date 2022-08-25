<?php


namespace nyuwa\traits;


trait JmesPathTrait
{


    /**
     * 接口字段提取处理器
     */
    public function handleTransform(){


    }

    /**
     * 使用jmespath转换数据,按单个操作
     * @param $array
     * @param array $originJson
     * @param $basicExp 基础父级表达式
     */
    public function handleJmesPathParse($exp,array $originJson,$basicExp = ""){
        if (is_string($exp)){
            $jmesPathExp = $exp;
            return \JmesPath\search($jmesPathExp,$originJson);
        }
        $newArray = [];
        foreach ($exp as $key => $jmesPathExp){
            if (empty($jmesPathExp)){
                $newArray[$key] = "";
            }else{
                $val = \JmesPath\search($jmesPathExp,$originJson);
                if (empty($val)){
                    $newArray[$key] = "";
                }else{
                    $newArray[$key] = $val;
                }
            }
        }
        return $newArray;
    }


    public function test($expression,$json){
        $info = \JmesPath\search($expression,nyuwa_is_json($json));

        return $info;
    }




}