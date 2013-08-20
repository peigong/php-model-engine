<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ModelEngineRoot . 'inc/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层数据字段默认值类。
 */
class BSystemList extends BObject implements IBSystemList{
    private $custom;
    
    /*- IBSystemList 接口实现 START -*/
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
        $attributes = $this->manager->getAttributes(MODEL_TYPE_SYSTEMLIST);
        $rows = $this->dao->getList();
        foreach ($rows as $idx => $row) {
            $entity = $this->manager->getAttributeValues(MODEL_TYPE_SYSTEMLIST, $row['id'], $attributes);
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
        array_push($result, array('text' => '非列表属性', 'value' => '0'));
        $system_list = $this->dao->getList();
        if(count($system_list) > 0){
            array_push($result, array('group' => '1', 'label' => '系统内置可选列表'));
            foreach($system_list as $sysli){
                array_push($result, array('text' => $sysli['name'], 'value' => '-' . $sysli['id']));
            }
        }
        $custom_list = $this->custom->fetchSystemList(array());
        if(count($custom_list) > 0){
            array_push($result, array('group' => '1', 'label' => '自定义可选列表'));
            foreach($custom_list as $cusli){
                array_push($result, $cusli);
            }
        }
        return $result;
    }
    /*- ISystemListFetch 接口实现 END -*/
    
    /**
     * 根据系统内置列表的ID，获取一个系统内置列表的数据实体。
     * @param $id {Int} 系统内置列表的ID。
     * @return {mixed} 系统内置列表的数据实体。
     */
    public function getEntity($id){
        return $this->dao->getEntity($id);
    }
    /*- IBSystemList 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /*- 私有方法 END -*/
}
?>
