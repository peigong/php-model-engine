<?php
require_once(dirname(__FILE__) . "/../config.inc.php");
require_once(ModelEngineRoot . 'inc/modelengine.inc.php');

$model = isset($_POST['model']) ? $_POST['model'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$attr = isset($_POST['attr']) ? $_POST['attr'] : 0;

if((strlen($model) > 0) && ($id > 0)){
    $manager = $context->getBean('modelengine.business.model');
    $manager->removeAttributeValue($model, $id, $attr);
    echo "1";
}else{
    echo "0";
}
?>
