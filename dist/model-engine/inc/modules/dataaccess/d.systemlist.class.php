<?php
require_once(ROOT . 'inc/core/d.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/dataaccess/d.modelengine.inc.php');

/**
 * 模型引擎系统数据层系统内置列表类。
 */
class DSystemList extends DObject implements IDSystemList {    
    private $db = null; 
    private $sql_init = null; 
    private $table = null; 
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->table = 'modelengine_core_system_lists'; 
        $this->sql_init = realpath(implode('/', array(ModelEngineRoot, 'sql', 'sqlite', 'modelengine.core'))); 
    }
    
    /**
     * 构造函数。
     */
    function DSystemList(){
        $this->__construct();
    }
    
    /*- IDSystemList 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
    
    /**
     * 根据系统内置列表的ID，获取一个系统内置列表的数据实体。
     * @param $id {Int} 系统内置列表的ID。
     * @return {mixed} 系统内置列表的数据实体。
     */
    public function getEntity($id){
        $this->initialise();
        $conditions = 'list_id = ' . $id;
        $settings = array(
            'table' => $this->table, 
            'fields' =>array(
                'id' => 'list_id',
                'name' => 'list_name',
                'clazz' => 'list_clazz'
            ),
            'conditions' => $conditions
        );
        return $this->retrieveLine($this->db, $settings);
    }
    
    /**
     * 获取系统内置列表。
     * @return Array 系统内置列表
     */
    public function getList(){
        $this->initialise();
        $conditions = '';
        $settings = array(
            'table' => $this->table, 
            'fields' =>array(
                'id' => 'list_id',
                'name' => 'list_name'
            ),
            'conditions' => $conditions,
            'order' => 'position_order asc'
        );
        return $this->retrieve($this->db, $settings);
    }
    /*- IDSystemList 接口实现 END -*/
    
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
