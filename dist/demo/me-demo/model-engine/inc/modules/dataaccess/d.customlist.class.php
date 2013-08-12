<?php
require_once(ROOT . 'inc/core/d.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/dataaccess/d.modelengine.inc.php');

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
        $this->sql_init = implode('/', array(ROOT, 'model-engine', 'sql', 'sqlite', 'modelengine.core')); 
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
