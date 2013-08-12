<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层用户自定义属性列表项类。
 */
class BCustomListItem extends BObject implements IBCustomListItem{    
    /*- IBCustomListItem 接口实现 START -*/
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
        $attributes = $this->manager->getAttributes(MODEL_TYPE_CUSTOMLISTITEM);
        $rows = $this->dao->getListItems($code);
        foreach ($rows as $idx => $row) {
            $entity = $this->manager->getAttributeValues(MODEL_TYPE_CUSTOMLISTITEM, $row['id'], $attributes);
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
        $listId = $options['listId'];
        if($listId){
            $result = $this->dao->getListItems($listId);
        }
        return $result;
    }
    /*- ISystemListFetch 接口实现 END -*/
    /*- IBCustomListItem 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /*- 私有方法 END -*/
}
?>
