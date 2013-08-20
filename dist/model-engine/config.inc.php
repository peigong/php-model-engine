<?php
/**
 * 系统目录。
 */
$global_config_path = realpath(dirname(__FILE__) . '/../../config.inc.php');
if (file_exists($global_config_path)) {
	require_once($global_config_path);
}
$global_config_path = realpath(dirname(__FILE__) . '/../config.inc.php');
if (file_exists($global_config_path)) {
	require_once($global_config_path);
}
/*- WEB系统根目录 -*/
if (!defined('ROOT')) {
	exit();
}

/*模型和模型表单引擎库的根目录*/
if (!defined('ModelEngineRoot')) {
	define('ModelEngineRoot', str_replace('\\', '/', dirname(__FILE__)) . '/');
}
/*'jQuery文件上传插件服务器端类的路径*/
if (!defined('ModelEngineRoot')) {
	define('jQueryFileUploadLibPath', ROOT . 'libs/jquery-file-upload-8.7.1/');
}

if (!defined('ModelEngineData')) {
	define('ModelEngineData', ROOT . '../../demo.data/');
}

if (!isset($context)) {
	require_once(ROOT . "inc/core/ioc/applicationcontextfactory.class.php");
	$applicationContextFactory = ApplicationContextFactory::getIntance();
	$context = $applicationContextFactory->create();
}
$context->setConfigPath(ModelEngineRoot . 'conf/ioc/root', ROOT);
$context->setConfigPath(ModelEngineRoot . 'conf/ioc/model-engine', ModelEngineRoot);

if (!isset($httpContext)) {
	require_once(ROOT . "inc/core/httpcontext.class.php");
	$httpContext = new HttpContext($context);
}
?>