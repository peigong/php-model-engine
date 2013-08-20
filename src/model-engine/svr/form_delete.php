<?php
require_once(dirname(__FILE__) . "/../config.inc.php");
require_once(ModelEngineRoot . 'inc/modelengine.inc.php');

$model = isset($_POST['model']) ? $_POST['model'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : 0;

if((strlen($model) > 0) && ($id > 0)){
    $manager = $context->getBean('modelengine.business.form');
    $manager->remove($model, $id);
    echo '1';
}else{
    echo '0';
}
?>
