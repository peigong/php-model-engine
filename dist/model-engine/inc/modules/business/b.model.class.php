<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层模型类。
 */
class BModel extends BObject implements IBModel{
    private $daoAttribute;
    
    /*- IBModel 接口实现 START -*/
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
        if(0 == strlen($code)){
            $code = -1;
        }
        $attributes = $this->getAttributes(MODEL_TYPE_MODEL);
        $rows = $this->dao->getList($code);
        foreach ($rows as $idx => $row) {
            $entity = $this->getAttributeValues(MODEL_TYPE_MODEL, $row['id'], $attributes);
            array_push($result, $entity);
        }
        return $result;
    }
    /*- IModelListFetch 接口实现 END -*/
    
    /**
     * 获取模型属性的列表。
     * @param $code {String} 模型的编码。
     * @param $editable {Boolean} 是否只列出可编辑属性。
     * @return 模型属性的列表。
     */
    public function getAttributes($code, $editable = null){
        return $this->daoAttribute->getList($code, $editable);
    }
    
    /**
     * 获取模型的数据实体。
     * @param $code {String} 模型的编码。
     * @return {mixed} 模型的数据实体。
     */
    public function getEntityByCode($code){
        return $this->dao->getEntityByCode($code);
    }
    
    /**
     * 获取模型属性值的列表。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $attributes {Array} 模型属性数据。
     * 格式：
     * array(array(
     *       'id' => '',
     *       'name' => '',
     *       'comment' => '',
     *       'type' => '',
     *       'default' => '',
     *       'ext' => '',
     *       'editable' => '',
     *       'autoupdate' => '',
     *       'primary' => '',
     *       'position' => '',
     *       'value' => ''
     * ),... ...)
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return {Array} 模型属性值的列表。。
     */
    public function getAttributeValues($code, $id, $attributes, $ext = array()){
        $result = array();
        $model_attributes = array();
        $ext_attributes = array();
        
        $entity = $this->getEntityByCode($code);
        $model_table = $entity['model'];
        $attribute_table = $entity['attribute'];
        
        if($model_table){
            foreach($attributes as $attribute){
                if(array_key_exists('ext', $attribute) && $attribute['ext']){
                    array_push($ext_attributes, $attribute);
                }else{
                    array_push($model_attributes, $attribute);
                }
            }
            $model_attributes = $this->dao->getAttributeValues($id, $model_table, $model_attributes, $ext);
            if($model_attributes && is_array($model_attributes)){
                $result = array_merge($result, $model_attributes);
            }
            if(($id > 0)
                && $attribute_table){
                $ext_attributes = $this->daoAttribute->getAttributeValues($id, $attribute_table, $ext_attributes, $ext);
                if($ext_attributes && is_array($ext_attributes)){
                    $result = array_merge($result, $ext_attributes);
                }
            }
        }
        return $result;
    }
    
    /**
     * 获取模型属性值的列表。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $attributes {Array} 模型属性数据。
     * 格式：
     * array(array(
     *       'id' => '',
     *       'name' => '',
     *       'comment' => '',
     *       'type' => '',
     *       'default' => '',
     *       'ext' => '',
     *       'editable' => '',
     *       'autoupdate' => '',
     *       'primary' => '',
     *       'position' => '',
     *       'value' => ''
     * ),... ...)
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return {Array} 模型属性值的列表。。
     */
    public function getAttributeValueList($code, $id, $attributes, $ext = array()){
        $result = array();
        $model_attributes = array();
        $ext_attributes = array();
        
        $entity = $this->getEntityByCode($code);
        $model_table = $entity['model'];
        $attribute_table = $entity['attribute'];
        
        if($model_table){
            foreach($attributes as $attribute){
                if(array_key_exists('ext', $attribute) && $attribute['ext']){
                    array_push($ext_attributes, $attribute);
                }else{
                    array_push($model_attributes, $attribute);
                }
            }
            $model_attributes = $this->dao->getAttributeValueList($id, $model_table, $model_attributes, $ext);
            if($model_attributes && is_array($model_attributes)){
                $result = array_merge($result, $model_attributes);
            }
            if(($id > 0)
                && $attribute_table){
                $ext_attributes = $this->daoAttribute->getAttributeValueList($id, $attribute_table, $ext_attributes, $ext);
                if($ext_attributes && is_array($ext_attributes)){
                    $result = array_merge($result, $ext_attributes);
                }
            }
        }
        return $result;
    }
    
    /**
     * 添加一个新的模型。
     * @param $code {String} 模型的编码。
     * @param $attributes {Array} 模型属性数据。
     * 格式：
     * array(array(
     *       'id' => '',
     *       'name' => '',
     *       'comment' => '',
     *       'type' => '',
     *       'default' => '',
     *       'ext' => '',
     *       'editable' => '',
     *       'autoupdate' => '',
     *       'primary' => '',
     *       'position' => '',
     *       'value' => ''
     * ),... ...)
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return Int 新添加的模型ID（异常为-1）。
     */
    public function create($code, $attributes, $ext = array()){
        $id = -1;
        $model_attributes = array();
        $ext_attributes = array();
        
        $entity = $this->getEntityByCode($code);
        $model_table = $entity['model'];
        $attribute_table = $entity['attribute'];
        
        if($model_table){
            foreach($attributes as $attribute){
                if(array_key_exists('ext', $attribute) && $attribute['ext']){
                    array_push($ext_attributes, $attribute);
                }else{
                    array_push($model_attributes, $attribute);
                }
            }
            if(count($model_attributes) > 0){
                $id = $this->dao->create($model_table, $model_attributes, $ext);
            }
            if(($id > 0)
                && $attribute_table
                && (count($ext_attributes) > 0)){
                $this->daoAttribute->create($code, $id, $attribute_table, $ext_attributes, $ext);
            }
        }
        return $id;
    }
    
    /**
     * 修改一个模型属性值。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $attributes {Array} 模型属性数据。
     * 格式：
     * array(array(
     *       'id' => '',
     *       'name' => '',
     *       'comment' => '',
     *       'type' => '',
     *       'default' => '',
     *       'ext' => '',
     *       'editable' => '',
     *       'autoupdate' => '',
     *       'primary' => '',
     *       'position' => '',
     *       'value' => ''
     * ),... ...)
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return Boolean 是否操作成功。
     */
    public function save($code, $id, $attributes, $ext = array()){
        $result = true;
        $model_attributes = array();
        $ext_attributes = array();
        
        $entity = $this->getEntityByCode($code);
        $model_table = $entity['model'];
        $attribute_table = $entity['attribute'];
        
        if(($id > 0) && $model_table){
            foreach($attributes as $attribute){
                if(array_key_exists('ext', $attribute) && $attribute['ext']){
                    array_push($ext_attributes, $attribute);
                }else{
                    array_push($model_attributes, $attribute);
                }
            }
            if(count($model_attributes) > 0){
                $check = $this->dao->save($id, $model_table, $model_attributes, $ext);
                $result = $result && $check;
            }
            if($attribute_table
                && (count($ext_attributes) > 0)){
                $check = $this->daoAttribute->save($code, $id, $attribute_table, $ext_attributes, $ext);
                $result = $result && $check;
            }
        }
        return $result;
    }
    
    /**
     * 删除一个模型。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
	 * @return Boolean 是否操作成功。
     * @param $ext {Array} 数据库切割需要的扩展参数。
     */
    public function remove($code, $id, $ext = array()){
        $result = true;
        $entity = $this->getEntityByCode($code);
        $model_table = $entity['model'];
        $attribute_table = $entity['attribute'];
        $result = $this->daoAttribute->removeValue($code, $id, $attribute_table, $ext);
        if($result){
            $attributes = $this->getAttributes($code);
            $result = $this->dao->remove($id, $model_table, $attributes, $ext);
        }
        return $result;
    }
    
    /**
     * 删除一个模型的属性值。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $attribute {Int} 模型属性的ID。
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return Boolean 是否操作成功。
     */
    public function removeAttributeValue($code, $id, $attribute, $ext = array()){
        $result = true;
        $entity = $this->getEntityByCode($code);
        $attribute_table = $entity['attribute'];
        if($attribute_table
            && $id > 0
            && $attribute > 0){
            $check = $this->daoAttribute->removeValue($code, $id, $attribute_table, $attribute, $ext);
            $result = $result && $check;
        }
        return $result;
    }
    /*- IBModel 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /*- 私有方法 END -*/
}
?>
