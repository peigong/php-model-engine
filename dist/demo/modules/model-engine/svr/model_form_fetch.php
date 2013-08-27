<?php
require_once(dirname(__FILE__) . "/../config.inc.php");

$id = isset($_GET['id']) ? $_GET['id'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';

$form = array();
$manager = $context->getBean('modelengine.business.form');
if(strlen($id) > 0){
    $form = $manager->getModelFormById($id);
}elseif(strlen($name) > 0){
    $form = $manager->getModelFormByName($name);
}
echo json_encode($form);
?>
