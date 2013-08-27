<?php
require_once(ROOT . 'inc/core/d.object.class.php');
require_once(ModelEngineRoot . 'inc/dataaccess/d.modelengine.inc.php');

/**
 * 模型引擎系统数据层模型模型类别类。
 */
class DModelCategory extends DObject implements IDModelCategory {    
    private $db = null; 
    private $sql_init = null; 
    private $table = null; 
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->table = 'modelengine_core_model_categories'; 
        $this->sql_init = realpath(implode('/', array(ModelEngineRoot, 'sql', 'sqlite', 'modelengine.core'))); 
    }
    
    /**
     * 构造函数。
     */
    function DModelCategory(){
        $this->__construct();
    }
    
    /*- IDModelCategory 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
    /**
     * 向数据库插入模型类别数据。
     * @param $name {String} 模型类别名称。
     * @param $description {String} 模型类别的描述信息。
     * @return {Int} 新增数据的ID。
     */
    public function add($name, $description){
        $this->initialise();
        $settings = array(
            'table' => $this->table, 
            'fields' => array(
                'category_name' => array('value' => $name, 'usequot' => true),
                'category_description' => array('value' => $description, 'usequot' => true),
                'parent_id' => array('value' => 0, 'usequot' => false),
                'position_order' => array('value' => 0, 'usequot' => false),
                'update_time' => array('value' => time(), 'usequot' => false),
                'create_time' => array('value' => time(), 'usequot' => false)
                )
            );
        return $this->insert($this->db, $settings);
    }
        
    /**
     * 获取模型类别的列表。
     * @return Array 模型类别的列表。
     */
    public function getList(){
        $this->initialise();
        $conditions = '';
        $settings = array(
            'table' => $this->table, 
            'fields' => array(
                'id' => 'category_id',
                'name' => 'category_name'
                ),
            'conditions' => $conditions
        );
        return $this->retrieve($this->db, $settings);
    }
    /*- IDModelCategory 接口实现 END -*/
    
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
