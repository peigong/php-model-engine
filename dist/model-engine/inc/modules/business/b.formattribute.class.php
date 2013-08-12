<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层表单属性类。
 */
class BFormAttribute extends BObject implements IBFormAttribute{    
    private $table;
    private $model;
    private $attribute;
    
    /**
     * 构造函数。
     */
    function  __construct(){
        $this->table = 'modelengine_core_form_attributes'; 
    }
    
    /**
     * 构造函数。
     */
    function BFormAttribute(){
        $this->__construct();
    }
    
    /*- IBFormAttribute 接口实现 START -*/
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
        $model = MODEL_TYPE_MODELFORM;
        if($ext['model']){
            $model = $ext['model'];
        }
        return $this->getAttributeValueList($model, $code);
    }
    /*- IModelListFetch 接口实现 END -*/
    
    /**
     * 根据模型的编码和ID，获取模型表单的扩展属性列表。
     * @param $code {String} 模型的编码。
     * @param $modelId {int} 模型ID。
     * @return {Array} 模型表单的扩展属性列表。
     */
    public function getAttributeValues($code, $modelId){
        $result = array();
        $attributes = $this->attribute->getList($code);
        $result = $this->attribute->getAttributeValues($modelId, $this->table, $attributes);
        return $result;
    }
    
    /**
     * 根据模型的编码和ID，获取模型表单可编辑的内置和扩展属性列表。
     * @param $code {String} 模型的编码。
     * @param $modelId {int} 模型ID。
     * @return {Array} 模型表单可编辑的内置和扩展属性列表。
     */
    public function getAttributeValueList($code, $modelId){
        $result = array();
        $attr = $this->model->getAttributes($code);
        $attributes = $this->model->getAttributeValueList($code, $modelId, $attr);
        foreach($attributes as $attribute){
            if(array_key_exists('editable', $attribute) && $attribute['editable']){
                $attribute['model_code'] = $code;
                array_push($result, $attribute);
            }
        }
        return $result;
    }
    /*- IBFormAttribute 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /*- 私有方法 END -*/
}
?>
