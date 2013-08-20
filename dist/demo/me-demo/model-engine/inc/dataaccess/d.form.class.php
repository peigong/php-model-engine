<?php
require_once(ROOT . 'inc/core/d.object.class.php');
require_once(ModelEngineRoot . 'inc/dataaccess/d.modelengine.inc.php');

/**
 * 模型引擎系统数据层模型表单类。
 */
class DForm extends DObject implements IDForm {    
    private $db = null; 
    private $sql_init = null; 
    private $table = null; 
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->table = 'modelengine_core_forms'; 
        $this->sql_init = realpath(implode('/', array(ModelEngineRoot, 'sql', 'sqlite', 'modelengine.core'))); 
    }
    
    /**
     * 构造函数。
     */
    function DForm(){
        $this->__construct();
    }
    
    /*- IDForm 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
    
    /**
     * 获取模型表单的数据实体。
     * @param $id {Int} 模型表单的ID。
     * @return {mixed} 模型表单的数据实体。
     */
    public function getEntityById($id){
        $this->initialise();
        $settings = $this->getListSettings('m.form_id = \'' . $id . '\'');
        $result = $this->retrieveLine($this->db, $settings);
        return $result;
    }
    
    /**
     * 获取模型表单的数据实体。
     * @param $name {String} 模型表单的名称。
     * @return {mixed} 模型表单的数据实体。
     */
    public function getEntityByName($name){
        $this->initialise();
        $settings = $this->getListSettings('m.form_name = \'' . $name . '\'');
        $result = $this->retrieveLine($this->db, $settings);
        return $result;
    }
    
    /**
     * 根据上级ID，获取模型表单的数据列表。
     * @param $parentId {int} 上级ID。
     * @return {Array} 模型表单的数据列表。
     */
    public function getListByParentId($parentId){
        $this->initialise();
        $settings = $this->getListSettings('m.parent_id = ' . $parentId);
        $result = $this->retrieve($this->db, $settings);
        return $result;
    }
    
    /**
     * 根据模型编码，获取模型表单的列表。
     * @param $code {String} 模型的编码。
     * @return 模型表单的列表。
     */
    public function getListByModel($code){
        $this->initialise();
        $settings = $this->getListSettings('m.form_mode_code = \'' . MODEL_TYPE_MODELFORM . '\' and m.model_code = \'' . $code . '\'');
        $result = $this->retrieve($this->db, $settings);
        return $result;
    }
    /*- IDForm 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /**
     * 初始化数据库。
     */
    private function initialise(){
        $this->db = $this->route->getDbByTable($this->table);
        if(!is_file($this->db)){
            $this->initialize($this->db, $this->sql_init);
        }
    }
    
    /**
     * 获取检索列表的检索配置数据。
     * @param $conditions {String} 检索条件。
     * @return {mixed} 检索列表的检索配置数据。
     */
    private function getListSettings($conditions){
        $settings = array(
            'table' => $this->table, 
            'alias' => 'm',
            'fields' => array(
                'id' => 'form_id', 
                'model' => 'model_code', 
                'code' => 'form_mode_code',
                'name' => 'form_name', 
                'description' => 'form_description',
                'leaves' => array('field' => 'leaves', 'expression' => 'select count(0) from ' . $this->table . ' as s where s.parent_id = m.form_id')
                ),
            'conditions' => $conditions,
            'order' => 'position_order asc'
        );
        return $settings;
    }
    /*- 私有方法 END -*/
}
?>
