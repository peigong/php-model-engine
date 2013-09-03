<?php
require_once(dirname(__FILE__) . "/config.inc.php");

$message = '';
$manager = $context->getBean('modelengine.business.tool');

$axn = isset($_POST['axn']) ? $_POST['axn'] : '';
switch ($axn) {
	case 'export-models':
		$codes = isset($_POST['model']) ? $_POST['model'] : array();
		$codes = array_unique($codes);
		if ($manager->export_models($codes)) {
			$message = '成功导出了模型数据！';
		}
		break;
	case 'export-categories':
		$ids = isset($_POST['category']) ? $_POST['category'] : array();
		$ids = array_unique($ids);
		if ($manager->export_categories($ids)) {
			$message = '成功导出了模型类别数据！';
		}
		break;
	case 'export-system-lists':
		$ids = isset($_POST['list']) ? $_POST['list'] : array();
		$ids = array_unique($ids);
		if ($manager->export_system_lists($ids)) {
			$message = '成功导出了系统内置属性值下拉列表数据！';
		}
		break;
	case 'export-custom-lists':
		$ids = isset($_POST['list']) ? $_POST['list'] : array();
		$ids = array_unique($ids);
		if ($manager->export_custom_lists($ids)) {
			$message = '成功导出了用户自定义属性值可选列表数据！';
		}
		break;
	case 'import-modules':
		$modules = isset($_POST['module']) ? $_POST['module'] : array();
		$modules = array_unique($modules);
		if ($manager->import_modules($modules)) {
			$message = '成功导入了系统模块的模型定义数据！';
		}
		break;
	case 'export-forms':
		$target = '';
		if (defined('STATIC_PATH')) {
			$target = STATIC_PATH;
		}
		if (strlen($target) > 0) {
			$manager->export_forms($target);
			$message = '成功导出了全部表单的配置数据！';
		}else{
			$message = '没有定义常量：STATIC_PATH！';
		}
		break;
	case 'backup':
		$time = time();
		$oldname = ModelEngineData . 'core/modelengine_core.sqlite';
		$newname = ModelEngineData . "core/modelengine_core.$time.sqlite";
		rename($oldname, $newname);
		$message = '数据库备份成功了！';
		break;
}

$models = $manager->get_models();
$categories = $manager->get_model_categories();
$system_lists = $manager->get_system_lists();
$custom_lists = $manager->get_custom_lists();
$files = $manager->get_export_files();
$modules = $manager->get_mdd_modules();

$page = $context->getBean('modelengine.pages.modeltoolpage');
$type = isset($_GET['type']) ? $_GET['type'] : 'tool';
$page->type = $type;
$page->assign('Models', $models);
$page->assign('Categories', $categories);
$page->assign('SystemLists', $system_lists);
$page->assign('CustomLists', $custom_lists);
$page->assign('Files', $files);
$page->assign('Modules', $modules);
$page->assign('Message', $message);
$page->render();
?>