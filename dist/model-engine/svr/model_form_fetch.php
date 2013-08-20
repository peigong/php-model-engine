<?php
require_once(dirname(__FILE__) . "/../config.inc.php");
$id = isset($_GET['id']) ? $_GET['id'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$model = isset($_GET['model']) ? $_GET['model'] : '';
$manager = $context->getBean('modelengine.business.form');
$manager->context = $context;
$form = array();
if(strlen($id) > 0){
    $form = $manager->getModelFormById($id, $model);
}elseif(strlen($name) > 0){
    $form = $manager->getModelFormByName($name, $model);
}
echo json_encode($form);
?>
