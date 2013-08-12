<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层模型属性类。
 */
class BAttribute extends BObject implements IBAttribute{    
    /*- IBAttribute 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
    
    /*- IModelListFetch 接口实现 START -*/
    /**
     * 获取模型列表。
     * @param $code {String || Int} 用于获取列表的条件编码。
     * @param $ext {Array} 扩展的附加条件字典。
     * @return 模型列表。
     */
    public function fetchModelList($code, $ext){
        $result = array();
        $editable = null;
        if($ext['editable']){
            $editable = 1;
        }
        $attributes = $this->manager->getAttributes(MODEL_TYPE_ATTRIBUTE);
        $rows = $this->dao->getList($code, $editable);
        foreach ($rows as $idx => $row) {
            $entity = $this->manager->getAttributeValues(MODEL_TYPE_ATTRIBUTE, $row['id'], $attributes);
            array_push($result, $entity);
        }
        return $result;
    }
    /*- IModelListFetch 接口实现 END -*/
    
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
        $code = $options['code'];
        $editable = $options['editable'];
        if($code){
            $attributes = $this->dao->getList($code, $editable);
            foreach($attributes as $attribute){
                array_push($result, array('text' => $attribute['comment'], 'value' => $attribute['name']));
            }
        }
        array_push($result, array('text' => '与数据库无关联', 'value' => ''));
        return $result;
    }
    /*- ISystemListFetch 接口实现 END -*/
    
    /**
     * 根据模型编码和属性名获取模型属性的数据实体。
     * @param $code {String} 模型的编码。
     * @param $field {String} 模型属性名。
     * @return Array 模型属性的数据实体。
     */
    public function getEntity($code, $field){
        return $this->dao->getEntity($code, $field);
    }
    /*- IBAttribute 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /*- 私有方法 END -*/
}
?>
