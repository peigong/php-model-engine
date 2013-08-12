<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层用户自定义属性列表类。
 */
class BCustomList extends BObject implements IBCustomList{    
    /*- IBCustomList 接口实现 START -*/
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
        $attributes = $this->manager->getAttributes(MODEL_TYPE_CUSTOMLIST);
        $rows = $this->dao->getList();
        foreach ($rows as $idx => $row) {
            $entity = $this->manager->getAttributeValues(MODEL_TYPE_CUSTOMLIST, $row['id'], $attributes);
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
        $list = $this->dao->getList();
        foreach($list as $li){
            array_push($result, array('text' => $li['name'], 'value' => $li['id']));
        }
        return $result;
    }
    /*- ISystemListFetch 接口实现 END -*/
    /*- IBCustomList 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /*- 私有方法 END -*/
}
?>
