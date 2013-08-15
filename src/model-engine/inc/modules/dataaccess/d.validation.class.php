<?php
require_once(ROOT . 'inc/core/d.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/dataaccess/d.modelengine.inc.php');

/**
 * 模型引擎系统数据层模型表单对象验证类。
 */
class DValidation extends DObject implements IDValidation {    
    private $db = null; 
    private $sql_init = null; 
    private $table = null; 
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->table = 'modelengine_core_validation'; 
        $this->sql_init = realpath(implode('/', array(ModelEngineRoot, 'sql', 'sqlite', 'modelengine.core'))); 
    }
    
    /**
     * 构造函数。
     */
    function DValidation(){
        $this->__construct();
    }
    
    /*- IDValidation 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
        
    /**
     * 获取模型表单对象验证方法的列表。
     * @param $formId {int} 表单对象的ID。
     * @return Array 模型表单对象验证方法的列表。
     */
    public function getList($formId){
        $this->initialise();
        $conditions = 'form_id = ' . $formId;
        $settings = array(
            'table' => $this->table, 
            'fields' => array(
                'id' => 'validation_id',
                'method' => 'validation_method',
                'message' => 'validation_message'
                ),
            'conditions' => $conditions
        );
        return $this->retrieve($this->db, $settings);
    }
    /*- IDValidation 接口实现 END -*/
    
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
    /*- 私有方法 END -*/
}
?>
