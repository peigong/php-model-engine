<?php
require_once(ROOT . 'inc/core/d.object.class.php');
require_once(ModelEngineRoot . 'inc/dataaccess/d.modelengine.inc.php');

/**
 * 模型引擎系统数据层用户自定义列表项类。
 */
class DCustomListItem extends DObject implements IDCustomListItem {    
    private $db = null; 
    private $sql_init = null; 
    private $table = null; 
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
        $this->table = 'modelengine_core_custom_listitems'; 
        $this->sql_init = realpath(implode('/', array(ModelEngineRoot, 'sql', 'sqlite', 'modelengine.core'))); 
    }
    
    /**
     * 构造函数。
     */
    function DCustomListItem(){
        $this->__construct();
    }
    
    /*- IDCustomListItem 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
        
    /**
     * 获取属性值可选列表的列表项。
     * @param $listId Int 列表编号。
     * @return Array 可选列表的列表项。
     */
    public function getListItems($listId){
        $this->initialise();
        $rows = array();
        $settings = array(
            'table' => $this->table,
            'fields' =>array(
                'id' => 'item_id',
                'value' => 'item_value',
                'text' => 'item_text'
            ),
            'conditions' => 'list_id = ' . $listId,
            'order' => 'position_order asc'
            );
        $rows = $this->retrieve($this->db, $settings);
        return $rows;
    }

    /**
    * 增加用户自定义属性列表项。
    * @param $list {Int} 所属列表的编号。
    * @param $value {String} 列表项的值。
    * @param $text {String} 列表项的文本。
    * @param $position {Int} 排序权重。
    */
    public function add($list, $value, $text, $position){
        $this->initialise();
        $settings = array(
            'table' => $this->table, 
            'fields' => array(
                'list_id' => array('value' => $list, 'usequot' => false),
                'item_value' => array('value' => $value, 'usequot' => true),
                'item_text' => array('value' => $text, 'usequot' => true),
                'position_order' => array('value' => $position, 'usequot' => false),
                'update_time' => array('value' => time(), 'usequot' => false),
                'create_time' => array('value' => time(), 'usequot' => false)
                )
            );
        return $this->insert($this->db, $settings);
    }
    /*- IDCustomListItem 接口实现 END -*/
    
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
