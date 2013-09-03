<?php
require_once(dirname(__FILE__) . "/../config.inc.php");

$id = isset($_GET['id']) ? $_GET['id'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';

$result = '{}';
$form = array();
$manager = $context->getBean('modelengine.business.form');
if(strlen($id) > 0){
    $form = $manager->getModelFormById($id);
    $result = json_encode($form);
}elseif(strlen($name) > 0){
	$json = implode('', array(STATIC_PATH, '/', 'forms', '/', $name, '.json'));
	if (defined('STATIC_PATH') && is_file($json)) {
		$result = file_get_contents($json);
	}else{
    	$form = $manager->getModelFormByName($name);
    	$result = json_encode($form);
	}
}
echo $result;
?>
