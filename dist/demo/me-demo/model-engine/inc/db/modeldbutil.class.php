<?php
require_once(ROOT . "inc/utils/io.func.php");
require_once(ModelEngineRoot . 'inc/db/modelengine.db.inc.php');

/**
 * 账户系统业务层工具类。
 */
class ModelDbUtil implements IModelDbUtil{  
    private $util = null;
    private $manager = null;
    private $b_attribute = null;
    
    /**
     * 构造函数。
     */
    function  __construct(){
        //parent::__construct();
    }
    
    /**
     * 构造函数。
     */
    function ModelDbUtil(){
        $this->__construct();
    }
    
    /*- IModelDbUtil 接口实现 START -*/
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/

    /**
    * 导入数据库。
    * @param $sql {String} 存储数据库SQL的目录。
    * @param $db {String} 数据库。
    */
    public function import($sql, $db){
        $this->util->import($sql, $db);
    }

    /**
    * 导入模型数据定义的属性对照表。
    * @param $module {String} 系统模块标识。
    * @param $name {String} 数据库名称。
    * @param $sql {String} 存储数据库SQL的目录。
    * @param $db {String} 数据库。
    */
    public function import_mmd($module, $name, $sql, $db){
        $mmd_path = implode('/', array($sql, $module, "$name.mmd.json"));
        if (is_file($mmd_path)) {
            $mmd = json_decode(file_get_contents($mmd_path), true);
            foreach ($mmd as $code => $attr) {
                $attr_update = array();
                $current_mmd = array();
                $attributes = $this->manager->getAttributes($code);
                foreach ($attributes as $idx => $attribute) {
                    if ($attribute['ext']) {
                        $current_mmd[$attribute['name']] = $attribute['id'];
                    }
                }
                foreach ($attr as $name => $id) {
                    $new_id = 0;
                    if (array_key_exists($name, $current_mmd)) {
                        $new_id = $current_mmd[$name];
                    }
                    array_push($attr_update, array('old' => $id, 'new' => $new_id));
                }
                $entity = $this->manager->getEntityByCode($code);
                $attribute_table = $entity['attribute'];
                $this->b_attribute->reset($db, $attribute_table, $attr_update);
            }
        }
    }

    /**
    * 导出数据库的数据。
    * @param $module {String} 系统模块标识。
    * @param $tmp {String} 输出文件的临时目录。
    * @param $name {String} 数据库名称。
    * @param $db {String} 数据库。
    * @param $tables {Array} 需要导出的数据表。
    * @param $ext {Array} 用于分库的扩展数据。
    */
    public function export_db($module, $tmp, $name, $db, $tables, $ext = array()){
        $path = implode('/', array($tmp, $module, $name));
        io_mkdir($path);
        if (array_key_exists('uid', $ext) && ($ext['uid'] > 0)) {
            $uid = $ext['uid'];
            if (0 == count($tables)) {
                $tables = $this->getTables($db);
            }
            $sql = $this->util->export($db, array('type' => 'table', 'objects' => $tables));
            $path = implode('/', array($path, 'u'));
            io_mkdir($path);
            file_put_contents(implode('', array($path, '/', $uid, '.sql')), $sql);
        }else{
            foreach ($tables as $idx => $table) {
                $sql = $this->util->export($db, array('type' => 'table', 'objects' => array($table)));
                file_put_contents(implode('', array($path, '/', $table, '.sql')), $sql);
            }
        }
    }

    /**
    * 导出模型数据定义的扩展属性对照表。
    * @param $module {String} 系统模块标识。
    * @param $tmp {String} 输出文件的临时目录。
    * @param $name {String} 数据库名称。
    * @param $tables {Array} 需要导出的数据表。
    */
    public function export_mmd($module, $tmp, $name, $tables){
        if ($tables && (count($tables) > 0)) {
            $dict = array();
            $models = $this->manager->fetchModelList('', array());
            foreach ($models as $idx => $model) {
                $attribute = $model['attribute_table'];
                if ($attribute) {
                    if (!array_key_exists($attribute, $dict)) {
                        $dict[$attribute] = array();
                    }
                    array_push($dict[$attribute], $model['model_code']);
                }
            }

            $mmd = array();
            foreach ($tables as $idx => $table) {
                if (array_key_exists($table, $dict)) {
                    $codes = $dict[$table];
                    foreach ($codes as $idx => $code) {
                        $mmd[$code] = array();
                        $attributes = $this->manager->getAttributes($code);
                        foreach ($attributes as $idx => $attribute) {
                            if ($attribute['ext']) {
                                $mmd[$code][$attribute['name']] = $attribute['id'];
                            }
                        }
                    }
                }
            }
            $path = implode('/', array($tmp, $module));
            io_mkdir($path);
            file_put_contents(implode('/', array($path, "$name.mmd.json")), json_encode($mmd));
        }

    }

    /**
    * 获取数据库的数据表列表。
    * @param $db {String} 数据库。
    */
    public function getTables($db){
        $result = array();
        $rows = $this->util->getObjects($db, DbObjectType_Table);
        foreach ($rows as $idx => $row) {
            array_push($result, $row['tbl_name']);
        }
        return $result;
    }
    /*- IModelDbUtil 接口实现 END -*/
    
    /*- 私有方法 START -*/
    /*- 私有方法 END -*/
}
?>
