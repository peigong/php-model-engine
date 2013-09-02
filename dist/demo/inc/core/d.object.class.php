<?php
require_once(ROOT . "inc/core/abstractobject.class.php");

/**
 * 数据层对象的基类。
 */
abstract class DObject extends AbstractObject{
    /**
     * 数据库访问对象。
     */
    protected $dao = null;
    
    /**
     * 数据库分库路由。
     */
    protected $route = null;
    
    /**
     * 数据库工具。
     */
    protected $util = null;
    
    /**
     * 初始化数据库。
     * @param $db {String} 要访问的数据库路径。
     * @param $sql {String} 用于初始化数据库的SQL文件路径。
     */
    protected function initialize($db, $sql){
        $this->util->import($sql, $db);
    }
    
    /**
     * 根据数据表名和用户ID，获取需要访问的数据库。
     * @param String $table 数据表名。
     * @param Int $uid 用户ID。
     * @return 需要访问的数据库。
     */
    protected function getDbByTable($table, $uid = null){
        return $this->route->getDbByTable($table, $uid);
    }
    
    /**
     * 向数据库中插入数据。
     * @param $db {String} 要访问的数据库路径。
     * @param $settings {mixed} 数据库检索的配置数据。
     * 格式：
     * array(
     *  'table' => '', 
     *  'fields' => array('field1' => array('value' => 'value1', 'usequot' => true), 
     *                    'field2' => array('value' => 'value2', 'usequot' => false)
     *                  )
     * )
	 * @return Int 新添加的ID（异常为-1）。
     */
    protected function insert($db, $settings){
        $fields = array_keys($settings['fields']);
        $values = array();
        foreach($fields as $idx=>$field){
            $field_settings = $settings['fields'][$field];
            if($field_settings['usequot']){
                array_push($values, implode('', array('\'', $field_settings['value'], '\'')));
            }else{
                array_push($values, $field_settings['value']);
            }
        }
        $sql = array();
        array_push($sql, 'INSERT INTO');
        array_push($sql, $settings['table']);
        array_push($sql, '(');
        array_push($sql, implode(',', $fields));
        array_push($sql, ') VALUES (');
        array_push($sql, implode(',', $values));
        array_push($sql, ');');
        $sql = implode(' ', $sql);
        return $this->dao->insert($sql, $db);
    }
    
    /**
     * 。
     * @param $db {String} 要访问的数据库路径。
     * @param $settings {mixed} 。
     */
    protected function delete($db, $settings){
        $sql = array();
        array_push($sql, 'DELETE FROM');
        array_push($sql, $settings['table']);
        array_push($sql, 'WHERE');
        array_push($sql, $settings['conditions']);
        $sql = implode(' ', $sql);
        return $this->dao->runSql($sql, $db);
    }
    
    /**
     * 更新数据。
     * @param $db {String} 要访问的数据库路径。
     * @param $settings {mixed} 。
     * 格式：
     * array(
     *  'table' => '', 
     *  'fields' => array('field1' => array('value' => 'value1', 'usequot' => true), 
     *                    'field2' => array('value' => 'value2', 'usequot' => false)
     *                  ),
     *  'conditions' => 'expression'
     * )
     */
    protected function update($db, $settings){
        $fields = array_keys($settings['fields']);
        $field_values = array();
        foreach($fields as $idx=>$field){
            $field_settings = $settings['fields'][$field];
            if($field_settings['usequot']){
                array_push($field_values, implode('', array($field, '=', '\'', $field_settings['value'], '\'')));
            }else{
                array_push($field_values, implode('', array($field, '=', $field_settings['value'])));
            }
        }
        $sql = array();
        array_push($sql, 'UPDATE');
        array_push($sql, $settings['table']);
        array_push($sql, 'SET');
        array_push($sql, implode(',', $field_values));
        array_push($sql, 'WHERE');
        array_push($sql, $settings['conditions']);
        $sql = implode(' ', $sql);
        return $this->dao->runSql($sql, $db);
    }
    
    /**
     * 执行数据库检索。
     * @param $db {String} 要访问的数据库路径。
     * @param $settings {mixed} 数据库检索的配置数据。
     * 格式：
     * array(
     *  'table' => '', 
     *  'alias' => '', 
     *  'fields' => array(
     *      'entity_field1' => 'db_field1', 
     *      'entity_field2' => 'db_field2',
     *      'entity_field3' => array('field' => 'Computed column', 'expression' => 'expression')
     *      ),
     *  'conditions' => 'expression'
     * )
     */
    protected function retrieve($db, $settings){
        $result = array();
        $entity_fields = array_keys($settings['fields']);
        $db_fields = array_values($settings['fields']);
        $db_fields = array_map(function($val){
                if(is_string($val)){
                    return $val;
                }
                if(is_array($val)){
                    return $val['field'];
                }
            }, $db_fields);
        $sql = $this->assembleRetrieveSql($db, $settings);
        $rows = $this->dao->getData($sql, $db);
        if ($rows && count($rows) > 0) {
            foreach($rows as $idx=>$row){
                $entity = array();
                for($i = 0; $i < count($entity_fields); $i++){
                    $entity[$entity_fields[$i]] = $row[$db_fields[$i]];
                }
                array_push($result, $entity);
            }
        }
        return $result;
    }
    
    /**
     * 执行数据库检索。
     * @param $db {String} 要访问的数据库路径。
     * @param $settings {mixed} 数据库检索的配置数据。
     * 格式：
     * array(
     *  'table' => '', 
     *  'alias' => '', 
     *  'fields' => array(
     *      'entity_field1' => 'db_field1', 
     *      'entity_field2' => 'db_field2',
     *      'entity_field3' => array('field' => 'Computed column', 'expression' => 'expression')
     *      ),
     *  'conditions' => 'expression'
     * )
     */
    protected function retrieveLine($db, $settings){
        $entity = array();
        $entity_fields = array_keys($settings['fields']);
        $db_fields = array_values($settings['fields']);
        $db_fields = array_map(function($val){
                if(is_string($val)){
                    return $val;
                }
                if(is_array($val)){
                    return $val['field'];
                }
            }, $db_fields);
        $sql = $this->assembleRetrieveSql($db, $settings);
        $row = $this->dao->getLine($sql, $db);
        if ($row && count($row) > 0) {
            for($i = 0; $i < count($entity_fields); $i++){
                $entity[$entity_fields[$i]] = $row[$db_fields[$i]];
            }
        }
        return $entity;
    }
    /**
     * 检查查询语句返回的记录第一个字段的值。
     * @param $db {String} 要访问的数据库路径。
     * @param $settings {mixed} 数据库检索的配置数据。
     * 格式：
     * array(
     *  'table' => '', 
     *  'alias' => '', 
     *  'fields' => array(
     *      'entity_field' => 'expression', 
     *      'entity_field2' => 'db_field2',
     *      'entity_field3' => array('field' => 'Computed column', 'expression' => 'expression')
     *      ),
     *  'conditions' => 'expression'
     * )
     */
    protected function getVar($db, $settings){
        $sql = $this->assembleRetrieveSql($db, $settings);
        return $this->dao->getVar($sql, $db);
    }
    
    /**
     * 拼装数据库检索语句的SQL。
     * @param $db {String} 数据库。
     * @param $settings {mixed} 数据库检索的配置数据。
     * 格式：
     * array(
     *  'table' => '', 
     *  'alias' => '', 
     *  'fields' => array(
     *      'entity_field1' => 'db_field1', 
     *      'entity_field2' => 'db_field2',
     *      'entity_field3' => array('field' => 'Computed column', 'expression' => 'expression')
     *      ),
     *  'conditions' => 'expression'
     * )
     * @return {String} 数据库检索语句的SQL。
     */
    private function assembleRetrieveSql($db, $settings){
        $entity_fields = array_keys($settings['fields']);
        $db_fields = array_values($settings['fields']);
        $db_fields = array_map(function($val){
                if(is_string($val)){
                    return $val;
                }
                if(is_array($val)){
                    $field = array();
                    array_push($field, '(');
                    array_push($field, $val['expression']);
                    array_push($field, ')');
                    array_push($field, 'AS');
                    array_push($field, $val['field']);
                    return implode(' ', $field);
                }
            }, $db_fields);
        $sql = array('SELECT');
        array_push($sql, implode(',', $db_fields));
        array_push($sql, 'FROM');
        array_push($sql, $settings['table']);
        if(array_key_exists('alias', $settings) && (strlen($settings['alias']) > 0)){
            array_push($sql, 'AS');
            array_push($sql, $settings['alias']);
        }
        if(array_key_exists('conditions', $settings) && (strlen($settings['conditions']) > 0)){
            array_push($sql, 'WHERE');
            array_push($sql, $settings['conditions']);
        }
        if(array_key_exists('order', $settings) && (strlen($settings['order']) > 0)){
            array_push($sql, 'ORDER BY');
            array_push($sql, $settings['order']);
        }
        return implode(' ', $sql);
    }
}
?>
