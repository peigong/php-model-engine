<?php
/**
 * 模型和表单引擎数据库工具类的接口。
 */
interface IModelDbUtil extends IInjectEnable{
    /**
    * 导入数据库。
    * @param $sql {String} 存储数据库SQL的目录。
    * @param $db {String} 数据库。
    */
    function import($sql, $db);

    /**
    * 导入模型数据定义的属性对照表。
    * @param $module {String} 系统模块标识。
    * @param $name {String} 数据库名称。
    * @param $sql {String} 存储数据库SQL的目录。
    * @param $db {String} 数据库。
    */
    function import_mmd($module, $name, $sql, $db);

    /**
    * 导出数据库的数据。
    * @param $module {String} 系统模块标识。
    * @param $tmp {String} 输出文件的临时目录。
    * @param $name {String} 数据库名称。
    * @param $db {String} 数据库。
    * @param $tables {Array} 需要导出的数据表。
    * @param $ext {Array} 用于分库的扩展数据。
    */
    function export_db($module, $tmp, $name, $db, $tables, $ext = array());

    /**
    * 导出模型数据定义的扩展属性对照表。
    * @param $module {String} 系统模块标识。
    * @param $tmp {String} 输出文件的临时目录。
    * @param $name {String} 数据库名称。
    * @param $tables {Array} 需要导出的数据表。
    */
    function export_mmd($module, $tmp, $name, $tables);

	/**
	* 获取数据库的数据表列表。
	* @param $db {String} 数据库。
	*/
	function getTables($db);
}
?>
