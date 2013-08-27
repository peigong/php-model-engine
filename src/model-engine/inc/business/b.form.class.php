<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ModelEngineRoot . 'inc/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层模型表单类。
 */
class BForm extends BObject implements IBForm{
    private $b_model;
    private $b_attribute;
    private $b_formattribute;
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
     * 根据上级ID，获取模型表单的数据列表。
     * @param $parentId {int} 上级ID。
     * @return {Array} 模型表单的数据列表。
     */
    public function getListByParentId($parentId){
        return $this->dao->getListByParentId($parentId);
    }
    
    /**
     * 根据模型表单的ID，获取一个模型表单的配置数据。
     * @param $id {Int} 模型表单的ID。
     * @return {mixed} 模型表单的配置数据。
     */
    public function getModelFormById($id){
        $entity = $this->dao->getEntityById($id);
        return $this->getModelFormByEntity($entity);
    }
    
    /**
     * 根据模型表单的名称，获取一个模型表单的配置数据。
     * @param $name {String} 模型表单的名称。
     * @return {mixed} 模型表单的配置数据。
     */
    public function getModelFormByName($name){
        $entity = $this->dao->getEntityByName($name);
        return $this->getModelFormByEntity($entity);
    }
    
    /**
     * 复制一个模型表单。
     * @param $id {Int} 模型表单的ID。
     */
    public function copy($id){
        $attributes = $this->b_model->getAttributes(MODEL_TYPE_MODELFORM);
        $entity = $this->b_model->getAttributeValues(MODEL_TYPE_MODELFORM, $id, $attributes);
        $form = $this->load_form_data($entity);
        $this->import_form_data($form, 0);
    }

    /**
    * 装载一个表单对象的子对象及表单验证对象等数据。
    * @param $form {Array} 待装载的表单初始数据对象。
    * @return {Array} 表单完整数据对象。
    */
    public function load_form_data($form){
        $form_id = $form['form_id'];

        $forms = array();
        $rows = $this->getListByParentId($form_id);
        foreach ($rows as $idx => $row) {
            $code = $row['code'];
            $attributes = $this->b_model->getAttributes($code);
            $entity = $this->b_model->getAttributeValues($code, $row['id'], $attributes);
            array_push($forms, $this->load_form_data($entity));
        }
        $form['forms'] = $forms;

        $validations = $this->b_validation->fetchModelList($form_id, array());
        $form['validations'] = $validations;

        return $form;
    }

    /**
    * 向数据库导入一个表单完整数据对象。
    * @param $form {Array} 待导入的表单完整数据对象。
    * @param $parent {Array} 表单对象的父ID。
    */
    public function import_form_data($form, $parent){
        $code = $form['form_mode_code'];
        $forms = $form['forms'];
        $validations = $form['validations'];

        $form['parent_id'] = $parent;
        $attributes = $this->b_model->getAttributes($code);
        if(count($attributes) > 0){
            foreach($attributes as &$attribute){
                $name = $attribute['name'];
                if (array_key_exists($name, $form)) {
                    $value = $form[$name];
                    $value = isset($value) ? $value : '';
                    $attribute['value'] = $value;
                }
            }
            $form_id = $this->b_model->create($code, $attributes);

            $attr = $this->b_model->getAttributes(MODEL_TYPE_VALIDATION);
            if(count($attr) > 0){
                foreach ($validations as $idx => $validation) {
                    $validation['form_id'] = $form_id;
                    foreach($attr as &$att){
                        $name = $att['name'];
                        if (array_key_exists($name, $validation)) {
                            $value = $validation[$name];
                            $value = isset($value) ? $value : '';
                            $att['value'] = $value;
                        }
                    }
                    $validation_id = $this->b_model->create(MODEL_TYPE_VALIDATION, $attr);
                }
            }
        }

        foreach ($forms as $idx => $sub) {
            $this->import_form_data($sub, $form_id);
        }
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
    private function getModelFormByEntity($entity){
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
                $form['items'] = $this->getModelFormLeaves($id);
            }
        }
        return $form;
    }
    
    private function getModelFormLeaves($parentId){
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
                $validations = array();
                /*忽略的属性*/
                $ignore_attributes = array('validation_id', 'model_code', 'form_id', 'position_order', 'update_time', 'create_time');
                $rows = $this->b_validation->fetchModelList($id, array());
                foreach ($rows as $idx => $row) {
                    $validation = array();
                    foreach ($row as $key => $val) {
                        if (!in_array($key, $ignore_attributes)) {
                            $validation[$key] = $val;
                        }
                    }
                    if (count($validation) > 0) {
                        array_push($validations, $validation);
                    }
                }
                $form['validations'] = $validations;
                
                // 获取表单对象的下级对象集合
                $form['items'] = array();
                if($item['leaves'] > 0){//如果有子对象，那么items属性就是子对象的列表
                    $form['items'] = $this->getModelFormLeaves($id);
                }

                // 如果表单对象有对应的数据库字段
                if(array_key_exists('field', $attributes) && $attributes['field']){
                    $field = $attributes['field'];
                    $attribute = $this->b_attribute->getEntity($model, $field);
                    // 指定模型的对应数据库字段，是一个能够从列表中选择数据的属性。
                    if(array_key_exists('list', $attribute)){
                        $list = $attribute['list'];
                        if ($list > 0) {
                            $form['list'] = array('type' => 'custom', 'id' => $list);
                        }else if ($list < 0) {
                            $form['list'] = array('type' => 'system', 'id' => abs($list));
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
