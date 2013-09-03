<?php
require_once(ROOT . 'inc/core/b.object.class.php');
require_once(ROOT . "inc/utils/io.func.php");
require_once(ModelEngineRoot . 'inc/business/b.modelengine.inc.php');

/**
 * 模型引擎系统业务层工具类。
 */
class BModelTool extends BObject implements IBModelTool{   
    private $model = null;
    private $attribute = null;
    private $form = null;
    private $validation = null;
    private $category = null;
    private $system = null;
    private $custom = null;
    private $item = null;
    private $tmp = '';
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->tmp = ModelEngineRoot . 'tmp/';
        io_mkdir($this->tmp . 'models');
    }
    
    /**
     * 构造函数。
     */
    function BModelTool(){
        $this->__construct();
    }

    /*- IBModelTool 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/

    /**
    * 获取系统中的模型列表。
    */
    public function get_models(){
        return $this->model->fetchModelList(-1, array());
    }

    /**
    * 获取系统中的模型列表。
    */
    public function get_model_categories(){
        return $this->category->fetchModelList(-1, array());
    }

    /**
    * 获取系统内置属性值下拉列表。
    */
    public function get_system_lists(){
        return $this->system->fetchModelList(-1, array());
    }

    /**
    * 获取用户自定义属性值可选列表。
    */
    public function get_custom_lists(){
        return $this->custom->fetchModelList(-1, array());
    }

    /**
    * 具备模型定义数据的系统模块。
    */
    public function get_mdd_modules(){
        $result = array();
        $path = ROOT . 'modules';
        if($dh = opendir($path)){
            while(false !== ($file = readdir($dh))){
                if (file_exists(implode('/', array($path, $file, 'mdd')))) {
                    array_push($result, $file);
                }
            }
        }
        return $result;
    }
    
    /**
    * 导出模型的数据。
    * @param $codes {Array} 需要导出的模型编码列表。
    * @return 导出是否成功。
    */
    public function export_models($codes){
        $result = false;
        $dict = array();
        $models = $this->get_models();
        if (count($models) > 0) {
            foreach ($models as $idx => $model) {
                $code = $model['model_code'];
                if (in_array($code, $codes)) {
                    $dict[$code] = array('model' => $model);
                    $dict[$code]['attributes'] = $this->model->getAttributes($code);
                    $dict[$code]['forms'] = array();
                    $forms = $this->form->fetchModelList($code, array());
                    foreach ($forms as $idx => $form) {
                        array_push($dict[$code]['forms'], $this->form->load_form_data($form));
                    }
                }
            }
            foreach ($dict as $code => $data) {
                file_put_contents($this->tmp . "models/$code.json", json_encode($data));
            }
            $result = true;
        }
        return $result;
    }
    
    /**
    * 导出模型类别的数据。
    * @param $ids {Array} 需要导出的模型类别编号的列表。
    */
    public function export_categories($ids){
        $result = false;
        $data = array();
        $categories = $this->get_model_categories();
        if (count($categories) > 0) {
            foreach ($categories as $idx => $category) {
                if (in_array($category['category_id'], $ids)) {
                    array_push($data, $category);
                }
            }
            file_put_contents($this->tmp . "/categories.json", json_encode($data));
            $result = true;
        }
        return $result;
    }
    
    /**
    * 导出系统内置属性值下拉列表的数据。
    * @param $ids {Array} 需要导出的模型类别编号的列表。
    */ 
    public function export_system_lists($ids){
        $result = false;
        $data = array();
        $system_lists = $this->get_system_lists();
        if (count($system_lists) > 0) {
            foreach ($system_lists as $idx => $list) {
                if (in_array($list['list_id'], $ids)) {
                    array_push($data, $list);
                }
            }
            file_put_contents($this->tmp . "/system_lists.json", json_encode($data));
            $result = true;
        }
        return $result;
    }
    
    /**
    * 导出用户自定义属性值可选列表的数据。
    * @param $ids {Array} 需要导出的模型类别编号的列表。
    */
    public function export_custom_lists($ids){
        $result = false;
        $data = array();
        $custom_lists = $this->get_custom_lists();
        if (count($custom_lists) > 0) {
            foreach ($custom_lists as $idx => $list) {
                $id = $list['list_id'];
                if (in_array($id, $ids)) {
                    $list['items'] = $this->item->fetchModelList($id, array());
                    array_push($data, $list);
                }
            }
            file_put_contents($this->tmp . "/custom_lists.json", json_encode($data));
            $result = true;
        }
        return $result;
    }

    /**
    * 导出模型和表单引擎系统中的所有表单对象配置。
    */
    public function export_forms($target){
        $forms = $this->form->getListByParentId(0);
        foreach ($forms as $idx => $form) {
            $id = $form['id'];
            $name = $form['name'];

            $path = implode('/', array($target, 'forms'));
            io_mkdir($path);
            $path .= "/$name.json";

            $comment = '';//'/*- export from model-engine -*/';
            $data = $this->form->getModelFormById($id);
            file_put_contents($path, $comment . json_encode($data));
        }
    }

    /**
    * 导入系统模块的模型定义数据。
    * @param $modules {Array} 需要导入的系统模块列表。
    */
    public function import_modules($modules){
        $result = true;
        $dict = array();
        foreach ($modules as $idx => $module) {
            $path = implode('/', array(ROOT . 'modules', $module, 'mdd'));
            $dict_categories = $this->import_categories($path);
            $dict_lists = $this->import_lists($path);

            /*模型数据*/
            $models_path = $path . '/models';
            if (is_dir($models_path) && ($dh = opendir($models_path))) {
                while(false !== ($file = readdir($dh))){
                    $model_path = implode('/', array($models_path, $file));
                    if (is_file($model_path)) {
                        $data = json_decode(file_get_contents($model_path), true);
                        $model = $data['model'];
                        $code = $model['model_code'];
                        $this->import_model($model, $dict_categories);
                        $this->import_attributes($code, $data['attributes'], $dict_lists);
                        $dict[$file] = $data;
                    }
                }
            }
        }
        foreach ($dict as $file => $data) {
            foreach ($data['forms'] as $idx => $form) {
                $this->form->import_form_data($form, 0);
            }
        }
        return $result;
    }

    /**
    * 获取已经导出了的JSON文件列表。
    */
    public function get_export_files(){
        $result = array();
        $tmp = $this->tmp . 'models';
        if($dh = opendir($tmp)){
            while(false !== ($file = readdir($dh))){
                if (is_file(implode('/', array($tmp, $file)))) {
                    array_push($result, $file);
                }
            }
        }
        return $result;
    }
    /*- IBModelTool 接口实现 END -*/
    
    /*- 私有方法 START -*/
    private function import_categories($path){
        $dict = array();
        /*模型类别数据*/
        $categories_file = $path . '/categories.json';
        if (is_file($categories_file)) {
            $categories = json_decode(file_get_contents($categories_file), true);
            if (count($categories) > 0) {
                foreach ($categories as $idx => $category) {
                    $category_id = $this->category->add($category['category_name'], $category['category_description']);
                    if ($category_id > 0) {
                        $dict[$category['category_id']] = $category_id;
                    }
                }
            }
        }
        return $dict;
    }
    private function import_lists($path){
        $dict = array();
        /*系统内置属性值下拉列表的数据*/
        $system_lists_file = $path . '/system_lists.json';
        if (is_file($system_lists_file)) {
            $system_lists = json_decode(file_get_contents($system_lists_file), true);
            if (count($system_lists) > 0) {
                foreach ($system_lists as $idx => $list) {
                    $list_id = $this->system->add($list['list_name'], $list['list_description'], $list['list_clazz'], $list['position_order']);
                    if ($list_id > 0) {
                        $dict['-' . $list['list_id']] = '-' . $list_id;
                    }
                }
            }
        }

        /*用户自定义属性值可选列表的数据*/
        $custom_lists_file = $path . '/custom_lists.json';
        if (is_file($custom_lists_file)) {
            $custom_lists = json_decode(file_get_contents($custom_lists_file), true);
            if (count($custom_lists) > 0) {
                foreach ($custom_lists as $idx => $list) {
                    $list_id = $this->custom->add($list['list_name'], $list['list_description'], $list['position_order']);
                    if ($list_id > 0) {
                        $dict[$list['list_id']] = $list_id;
                        if (array_key_exists('items', $list) && (count($list['items']) > 0)) {
                            foreach ($list['items'] as $i => $item) {
                                $item_id = $this->item->add($list_id, $item['item_value'], $item['item_text'], $item['position_order']);
                            }
                        }
                    }
                }
            }
        }
        return $dict;
    }
    private function import_model($model, $dict_categories){
        $category_id = 0;
        if (array_key_exists($model['category_id'], $dict_categories)) {
            $category_id = $dict_categories[$model['category_id']];
        }
        return $this->model->add($model['model_code'], $model['model_name'], $model['model_description'], $model['model_table'], $model['attribute_table'], $category_id);
    }
    private function import_attributes($code, $attributes, $dict_lists){
        foreach ($attributes as $idx => $attribute) {
            $category_id = 0;
            $list = $attribute['list'];
            if (array_key_exists($list, $dict_lists)) {
                $list = $dict_lists[$list];
            }
             
            $attribute_id = $this->attribute->add(
                $attribute['name'], 
                $attribute['comment'], 
                $attribute['type'], 
                $attribute['default'], 
                $code, 
                $list, 
                $attribute['ext'], 
                $attribute['editable'], 
                $attribute['autoupdate'], 
                $attribute['primary'], 
                $attribute['position'], 
                $category_id
            );
        }
    }
    /*- 私有方法 END -*/
}
?>
