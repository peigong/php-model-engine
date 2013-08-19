<?php
/**
 * 系统目录。
 */
/*- WEB系统根目录 -*/
$current_file_name = str_replace('\\', '/', dirname(__FILE__));
define('ROOT', realpath($current_file_name . '/../') . '/');

$server_name = $_SERVER['SERVER_NAME'];
$current__root = $current_file_name . '/';

define('SMARTY_PATH', ROOT . 'libs/Smarty_3_1_8/libs/');
define('SMARTY_TEMPLATES', ROOT . 'templates/');
define('SMARTY_CACHE', $current__root . 'cache/smarty/');

/*- 静态资源访问配置 -*/
define('STATIC_HOST', "http://$server_name/me-demo/static");
define('STATIC_PATH', $current__root . 'static');
/*- 数据库存储文件目录 -*/
define('ModelEngineData', $current__root . 'data/');
?>