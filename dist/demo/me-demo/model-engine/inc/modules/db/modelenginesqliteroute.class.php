<?php
require_once(ROOT . "inc/utils/io.func.php");
require_once(ROOT . "inc/core/d.route.inc.php");

/**
 * SQLite数据库分库路由。
 */
class ModelEngineSQLiteRoute implements IDbRoute{
    private $root = null; 
    
    /**
     * 构造函数。
     */
    function  __construct(){
        $this->root = '';
        if (defined('ModelEngineData')) {
            $this->root = ModelEngineData; 
        }elseif (defined('DATA_PATH')) {
            $this->root = DATA_PATH; 
        }
    }
    
    /**
     * 构造函数。
     */
    function ModelEngineSQLiteRoute(){
        $this->__construct();
    }

    /**
     * 根据数据表名和用户ID，获取需要访问的数据库。
     * @param $table {String} 数据表名。
     * @param $ext {Array} 数据库切割需要的扩展参数。
     * @return 需要访问的数据库。
     */
    public function getDbByTable($table, $ext = array()){
        $db = $this->root;
        $arr_table = explode('_', $table);
        if(count($arr_table) > 2){
            switch($arr_table[1]){
            case 'core':
                $db .= 'core/';
                break;
            case 'dict':
                $db .= 'dict/';
                break;
            case 'user':
                $uid = 0;
                if(array_key_exists('uid', $ext)){
                    $uid = $ext['uid'];
                }
                $db .= implode('/', array('u', ($uid % 10), $uid, ''));
                break;
            }
            io_mkdir($db);
            $db_name = implode('_', array($arr_table[0], $arr_table[1]));
            $db = implode('', array($db, $db_name, '.sqlite'));
        }else{
        }
        return $db;
    }
}
?>
