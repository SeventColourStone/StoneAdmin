<?php


namespace nyuwa\traits;


trait JmesPathTrait
{


    /**
     * 接口字段提取处理器
     */
    public function handleTransform(){


    }


    public function test($expression,$json){
        $info = \JmesPath\search($expression,nyuwa_is_json($json));

        return $info;
    }




}