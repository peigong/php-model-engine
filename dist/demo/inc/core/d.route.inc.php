<?php
/**
 * 账户系统业务层用户类的接口。
 */
interface IDbRoute{    
    /**
     * 根据数据表名和用户ID，获取需要访问的数据库。
     * @param $table {String} 数据表名。
     * @param $ext {Array} 数据库切割需要的扩展参数。
     * @return 需要访问的数据库。
     */
    function getDbByTable($table, $ext = array());
}
?>
