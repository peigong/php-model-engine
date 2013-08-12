<?php
require_once(ROOT . 'inc/core/d.object.class.php');
require_once(ModelEngineRoot . 'inc/modules/dataaccess/d.modelengine.inc.php');

/**
 * 模型引擎系统数据层数据字段默认值类。
 */
class DValueType extends DObject implements IDValueType {
    private $db = null; 
    private $sql_init = null; 
    private $table = null; 
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->table = 'modelengine_dict_value_types'; 
        $this->sql_init = implode('/', array(ROOT, 'model-engine', 'sql', 'sqlite', 'modelengine.dict')); 
    }
    
    /**
     * 构造函数。
     */
    function DValueType(){
        $this->__construct();
    }
    
    /*- IDValueType 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
        
    /**
     * 获取数据字段值类型的列表。
     * @return Array 数据字段值类型的列表。
     */
    public function getList(){
        $this->initialise();
        $conditions = '';
        $settings = array(
            'table' => $this->table, 
            'fields' => array(
                'code' => 'type_code',
                'name' => 'type_name'
                ),
            'conditions' => $conditions
        );
        return $this->retrieve($this->db, $settings);
    }
    /*- IDValueType 接口实现 END -*/
    
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
