<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层模型表单类。
 */
class BForm extends BObject implements IBForm{
    private $context;
    private $b_model;
    private $b_attribute;
    private $b_formattribute;
    private $b_system_list;
    private $b_custom_list_item;
    private $b_validation;
    
    /*- IBForm 接口实现 START -*/
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
        $attributes = $this->b_model->getAttributes(MODEL_TYPE_MODELFORM);
        $rows = $this->getListByModel($code);
        foreach ($rows as $idx => $row) {
            $entity = $this->b_model->getAttributeValues(MODEL_TYPE_MODELFORM, $row['id'], $attributes);
            array_push($result, $entity);
        }
        return $result;
    }
    /*- IModelListFetch 接口实现 END -*/
    
    /**
     * 根据模型编码，获取模型表单的列表。
     * @param $code {String} 模型的编码。
     * @return 模型表单的列表。
     */
    public function getListByModel($code){
        return $this->dao->getListByModel($code);
    }
    
    /**
     * 根据模型表单的ID，获取一个模型表单的配置数据。
     * @param $id {Int} 模型表单的ID。
     * @param $code {String} 模型的编码。
     * @return {mixed} 模型表单的配置数据。
     */
    public function getModelFormById($id, $code = ''){
        $entity = $this->dao->getEntityById($id);
        return $this->getModelFormByEntity($entity, $code);
    }
    
    /**
     * 根据模型表单的名称，获取一个模型表单的配置数据。
     * @param $name {String} 模型表单的名称。
     * @param $code {String} 模型的编码。
     * @return {mixed} 模型表单的配置数据。
     */
    public function getModelFormByName($name, $code = ''){
        $entity = $this->dao->getEntityByName($name);
        return $this->getModelFormByEntity($entity, $code);
    }
    
    /**
     * 复制一个模型表单对象。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型表单对象的ID。
	 * @return Boolean 是否操作成功。
     */
    public function copy($code, $id, $parentId = 0){
        $result = true;
        $model_id = -1;
        $attributes = $this->b_model->getAttributes($code);
        $attributeValues = $this->b_model->getAttributeValues($code, $id, $attributes);
        $new_attributes = array();
        foreach($attributes as $attribute){
            $value = $attributeValues[$attribute['name']];
            $attribute['value'] = isset($value) ? $value : '';
            //TODO:在业务层感知数据库具体字段，不太理想，是折衷方案。
            if('parent_id' == $attribute['name']){
                $attribute['value'] = $parentId;
            }
            array_push($new_attributes, $attribute);
        }
        $model_id = $this->b_model->create($code, $new_attributes);
        $models = $this->dao->getListByParentId($id);
        if(count($models) > 0){
            foreach($models as $model){
                $sub_code = $model['code'];
                $sub_id = $model['id'];
                $this->copy($sub_code, $sub_id, $model_id);
            }
        }
        return $result;
    }
    
    /**
     * 删除一个模型表单对象。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型表单对象的ID。
	 * @return Boolean 是否操作成功。
     */
    public function remove($code, $id){
        $result = true;
        $models = $this->dao->getListByParentId($id);
        if(count($models) > 0){
            foreach($models as $model){
                $sub_code = $model['code'];
                $sub_id = $model['id'];
                $check = $this->remove($sub_code, $sub_id);
                $result = $result && $check;
            }
        }
        $check = $this->b_model->remove($code, $id);
        $result = $result && $check;
        return $result;
    }
    /*- IBForm 接口实现 END -*/
    
    /*- 私有方法 START -*/
    private function getModelFormByEntity($entity, $parasitifer){
        $form = array();
        if($entity && array_key_exists('id', $entity)){
            $id = $entity['id'];
            $code = $entity['code'];
            $form['id'] = $id;
            $form['model'] = $entity['model'];
            $form['code'] = $code;
            $form['name'] = $entity['name'];
            //$form['description'] = $entity['description'];
            $form['attributes'] = $this->b_formattribute->getAttributeValues($code, $id);
            $form['items'] = array();
            if($entity['leaves'] > 0){
                $form['items'] = $this->getModelFormLeaves($id, $parasitifer);
            }
        }
        return $form;
    }
    
    private function getModelFormLeaves($parentId, $parasitifer){
        $result = array();
        $items = $this->dao->getListByParentId($parentId);
        foreach($items as $idx=>$item){
            if(array_key_exists('id', $item)){
                $id = $item['id'];
                $model = $item['model'];
                $code = $item['code'];
                
                $form['id'] = $id;
                $form['model'] = $model;
                $form['code'] = $code;
                $form['name'] = $item['name'];
                //$form['description'] = $item['description'];
                
                // 获取表单对象的属性集合
                $attributes = $this->b_formattribute->getAttributeValues($code, $id);
                $form['attributes'] = $attributes;
                
                // 获取表单对象的验证方法集合
                $validation = $this->b_validation->fetchModelList($id, array());
                $form['validation'] = $validation;
                
                // 获取表单对象的下级对象集合
                $form['items'] = array();
                if($item['leaves'] > 0){//如果有子对象，那么items属性就是子对象的列表
                    $form['items'] = $this->getModelFormLeaves($id, $parasitifer);
                }else{//没有子对象，
                    // 如果表单对象有对应的数据库字段
                    if(array_key_exists('field', $attributes) && $attributes['field']){
                        $field = $attributes['field'];
                        $attribute = $this->b_attribute->getEntity($model, $field);
                        // 指定模型的对应数据库字段，是一个能够从列表中选择数据的属性。
                        if(array_key_exists('list', $attribute)){
                            $listitems = array();
                            $list = $attribute['list'];
                            $default = $attribute['default'];
                            if($list > 0){
                                //用户自定义属性值列表
                                //实现了模型和表单引擎系统的ISystemListFetch接口的类
                                $options = array('listId' => $list);
                                $listitems = $this->b_custom_list_item->fetchSystemList($options);
                            }elseif(($list < 0) && $this->context && $this->b_system_list){//系统内置属性值列表
                                $entity = $this->b_system_list->getEntity(abs($list));
                                $clazz = $entity['clazz'];
                                if($clazz){
                                    //实现了模型和表单引擎系统的ISystemListFetch接口的类
                                    $system_list = $this->context->getBean($clazz);
                                    $options = array('code' => $parasitifer, 'editable' => true);
                                    $listitems = $system_list->fetchSystemList($options);
                                }
                            }
                            foreach($listitems as $listitem){
                                if(!$listitem['group']){
                                    $listitem['default'] = $default;
                                }
                                array_push($form['items'], $listitem);
                            }
                        }
                    }
                }
                array_push($result, $form);
            }
        }
        return $result;
    }
    /*- 私有方法 END -*/
}
?>
