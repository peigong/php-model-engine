<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层值类型类。
 */
class BValueType extends BObject implements IBValueType{    
    /*- IBValueType 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
    
    /*- ISystemListFetch 接口实现 START -*/
    /**
     * 获取系统内置列表。
     * @param $options {Array} 可选项。
     * @return 系统内置列表。
     * 数据格式：array(
     *   array('text' => '', 'value' => '')
     *   )
     */
    public function fetchSystemList($options){
        $result = array();
        $valueTypes = $this->dao->getList();
        foreach($valueTypes as $valueType){
            array_push($result, array('text' => $valueType['name'], 'value' => $valueType['code']));
        }
        return $result;
    }
    /*- ISystemListFetch 接口实现 END -*/
    /*- IBValueType 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /*- 私有方法 END -*/
}
?>
