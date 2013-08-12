<?php
require_once(ROOT . 'inc/core/d.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/dataaccess/d.modelengine.inc.php');

/**
 * 模型引擎系统数据层模型类。
 */
class DModel extends DObject implements IDModel {    
    private $db = null; 
    private $sql_init = null; 
    private $table = null; 
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->table = 'modelengine_core_models'; 
        $this->sql_init = implode('/', array(ROOT, 'model-engine', 'sql', 'sqlite', 'modelengine.core')); 
    }
    
    /**
     * 构造函数。
     */
    function DModel(){
        $this->__construct();
    }
    
    /*- IDModel 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
    
    /**
     * 获取模型的列表。
     * @param $cid {Int} 模型类别的ID。
     * @return Array 模型的列表。
     */
    public function getList($cid){
        $this->initialise();
        $conditions = '';
        if($cid > 0){
            $conditions = 'category_id = ' . $cid;
        }
        $order = 'category_id asc';
        $settings = $this->getListSettings($conditions, $order);
        return $this->retrieve($this->db, $settings);
    }
    
    /**
     * 获取模型的数据实体。
     * @param $code {String} 模型的编码。
     * @return {mixed} 模型的数据实体。
     */
    public function getEntityByCode($code){
        $this->initialise();
        $settings = $this->getListSettings('model_code = \'' . $code . '\'');
        return $this->retrieveLine($this->db, $settings);
    }
    
    /**
     * 获取模型内置属性值的列表。
     * @param $id {Int} 模型数据的ID。
     * @param $table {String} 模型数据存储的数据表。
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
	 * @return {Array} 模型内置属性值的列表。。
     */
    public function getAttributeValues($id, $table, $attributes, $ext = array()){
        $result = array();
        $fields = array();
        $conditions = '';
        foreach($attributes as $attribute){
            $name = $attribute['name'];
            $fields[$name] = $name;
            if(array_key_exists('primary', $attribute) && $attribute['primary']){
                $conditions = $name . '=' . $id;
            }
        }
        if(strlen($conditions) > 0){
            $settings = array(
                'table' => $table, 
                'fields' => $fields,
                'conditions' => $conditions
            );
            $db = $this->getDbByTable($table, $ext);
            $result = $this->retrieveLine($db, $settings);
        }
        return $result;
    }
    
    /**
     * 获取模型内置属性值的列表。
     * @param $id {Int} 模型数据的ID。
     * @param $table {String} 模型数据存储的数据表。
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
	 * @return {Array} 模型内置属性值的列表。。
     */
    public function getAttributeValueList($id, $table, $attributes, $ext = array()){
        $result = array();
        $row = $this->getAttributeValues($id, $table, $attributes, $ext);
        if(count($row)){
            $dict = array();
            foreach($attributes as $attribute){
                $dict[$attribute['name']] = $attribute;
            }
            foreach($row as $key=>$value){
                if(array_key_exists($key, $dict)){
                    $attr = array();
                    $attribute = $dict[$key];
                    $attr['model_id'] = $id;
                    $attr['attribute_id'] = '';
                    $attr['value'] = $value;
                    $attr['name'] = $attribute['name'];
                    $attr['ext'] = $attribute['ext'];
                    $attr['editable'] = $attribute['editable'];
                    array_push($result, $attr);
                }
            }
        }
        return $result;
    }
    
    /**
     * 添加一个新的模型。
     * @param $table {String} 模型数据存储的数据表。
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
    public function create($table, $attributes, $ext = array()){
        $settings = array(
            'table' => $table, 
            'fields' => array()
            );
        foreach($attributes as $attribute){
            $ignore = false;
            $value = '';
            if(array_key_exists('default', $attribute) && (strlen($attribute['default']) > 0)){
                switch($attribute['default']){
                case DEFAULT_VALUE_EMPTY:
                    $value = '';
                    break;
                case DEFAULT_VALUE_NOW:
                    $value = time();
                    break;
                case DEFAULT_VALUE_ZERO:
                    $value = 0;
                    break;
                case DEFAULT_VALUE_AUTOINCREMENT:
                default:
                    $ignore = true;
                }
            }else{
                $ignore = true;
            }
            if(array_key_exists('value', $attribute) && (strlen($attribute['value']) > 0)){
                $value = $attribute['value'];
            }
            switch($attribute['type']){
            case VALUE_TYPE_INT:
            case VALUE_TYPE_BOOL:
                $usequot = false;
                break;
            case VALUE_TYPE_STR:
            default:
                $usequot = true;
            }
            if(!$ignore
                && array_key_exists('name', $attribute) 
                && (strlen($attribute['name']) > 0)){
                $settings['fields'][$attribute['name']] = array('value' => $value, 'usequot' => $usequot);
            }
        }
        $result = -1;
        if(count($settings['fields']) > 0){
            $db = $this->getDbByTable($table, $ext);
            $result = $this->insert($db, $settings);
        }
        return $result;
    }
    
    /**
     * 修改一个模型属性值。
     * @param $id {Int} 模型数据的ID。
     * @param $table {String} 模型数据存储的数据表。
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
    public function save($id, $table, $attributes, $ext = array()){
        $result = false;
        $fields = array();
        $conditions = '';
        foreach($attributes as $attribute){
            $name = $attribute['name'];
            $value = $attribute['value'];
            if(array_key_exists('primary', $attribute) && $attribute['primary']){
                $conditions = $name . '=' . $id;
            }else{
                if($attribute['autoupdate']){
                    if(array_key_exists('default', $attribute) && (strlen($attribute['default']) > 0)){
                        switch($attribute['default']){
                        case DEFAULT_VALUE_EMPTY:
                            $value = '';
                            break;
                        case DEFAULT_VALUE_NOW:
                            $value = time();
                            break;
                        case DEFAULT_VALUE_ZERO:
                            $value = 0;
                            break;
                        case DEFAULT_VALUE_AUTOINCREMENT:
                        default:
                        }
                    }
                }
                switch($attribute['type']){
                case VALUE_TYPE_INT:
                case VALUE_TYPE_BOOL:
                    $usequot = false;
                    break;
                case VALUE_TYPE_STR:
                default:
                    $usequot = true;
                }
                $fields[$name] = array('value' => $value, 'usequot' => $usequot);
            }
        }
        if((count($fields) > 0) && (strlen($conditions) > 0)){
            $settings = array(
                'table' => $table, 
                'fields' => $fields,
                'conditions' => $conditions
            );
            $db = $this->getDbByTable($table, $ext);
            $this->update($db, $settings);
            $result = true;
        }
        return $result;
    }    
    
    /**
     * 删除一个模型。
     * @param $id {Int} 模型数据的ID。
     * @param $table {String} 模型数据存储的数据表。
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
    public function remove($id, $table, $attributes, $ext = array()){
        $conditions = '';
        foreach($attributes as $attribute){
            if(array_key_exists('primary', $attribute) && $attribute['primary']){
                $conditions = $attribute['name'] . '=' . $id;
            }
        }
        if(strlen($conditions) > 0){
            $settings = array(
                'table' => $table, 
                'conditions' => $conditions
            );
            $db = $this->getDbByTable($table, $ext);
            $this->delete($db, $settings);
        }
        return true;
    }
    /*- IDModel 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /**
     * 初始化数据库。
     */
    private function initialise(){
        $this->db = $this->getDbByTable($this->table);
        if(!is_file($this->db)){
            $this->initialize($this->db, $this->sql_init);
        }
    }
    
    /**
     * 获取检索列表的检索配置数据。
     * @param $conditions {String} 检索条件。
     * @param $order {String} 排序。
     * @return {mixed} 检索列表的检索配置数据。
     */
    private function getListSettings($conditions, $order = null){
        $settings = array(
            'table' => $this->table,
            'fields' => array(
                'id' => 'model_id',
                'code' => 'model_code',
                'name' => 'model_name',
                'description' => 'model_description',
                'model' => 'model_table',
                'attribute' => 'attribute_table'
                ),
            'conditions' => $conditions
        );
        if($order){
            $settings['order'] = $order;
        }
        return $settings;
    }
    /*- 私有方法 END -*/
}
?>
