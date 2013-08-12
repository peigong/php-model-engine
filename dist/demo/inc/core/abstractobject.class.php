<?php
/**
 * 通用的对象基类。
 */
abstract class AbstractObject{
    /**
     * 把源数组的内容复制到目标数据。
     * 有冲突时的正确做法是日志报错，当前的实现是直接覆盖。
     * @param Array $des 目标数组。
     * @param Array $src 来源数组。
     * @return 合并后的数据。
     */
    protected function merge($des, $src){
        foreach($src as $key=>$val){
            $des[$key] = $val;
        }
        return $des;
    }
}
?>
