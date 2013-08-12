<?php
/**
 * 应用程序上下文对象的接口。
 */
interface IApplicationContext{
    /**
     * 根据配置ID获取类实例。
     * @param String $id 配置ID。
     * @return 类实例。
     */
    function getBean($id);
    
    /**
     * 设置IOC类配置的目录。
     * @param String $path IOC类配置的目录。
     */
    function setConfigPath($path);
}

interface IInjectEnable{
    /**
     * 设置属性值。
     * @param String $prop 属性名。
     * @param mix $val 属性的值。
     */
    function __set($prop, $val);
}
?>
