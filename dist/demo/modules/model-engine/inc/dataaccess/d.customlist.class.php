<?php
require_once(ROOT . 'inc/core/d.object.class.php');
require_once(ModelEngineRoot . 'inc/dataaccess/d.modelengine.inc.php');

/**
 * 模型引擎系统数据层数据字段默认值类。
 */
class DCustomList extends DObject implements IDCustomList {    
    private $db = null; 
    private $sql_init = null; 
    private $table = null; 
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->table = 'modelengine_core_custom_lists'; 
        $this->sql_init = realpath(implode('/', array(ModelEngineRoot, 'sql', 'sqlite', 'modelengine.core'))); 
    }
    
    /**
     * 构造函数。
     */
    function DCustomList(){
        $this->__construct();
    }
    
    /*- IDCustomList 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
        
    /**
     * 获取用户自定义属性值可选列表。
     * @return Array 属性值可选列表。
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

    /**
    * 增加用户自定义属性列表。
    * @param $name {String} 列表的名称。
    * @param $description {String} 列表的描述信息。
    * @param $position {Int} 排序权重。
    */
    public function add($name, $description, $position){
        $this->initialise();
        $settings = array(
            'table' => $this->table, 
            'fields' => array(
                'list_name' => array('value' => $name, 'usequot' => true),
                'list_description' => array('value' => $description, 'usequot' => true),
                'position_order' => array('value' => $position, 'usequot' => false),
                'update_time' => array('value' => time(), 'usequot' => false),
                'create_time' => array('value' => time(), 'usequot' => false)
                )
            );
        return $this->insert($this->db, $settings);
    }
    /*- IDCustomList 接口实现 END -*/
    
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
