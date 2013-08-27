<?php
require_once(dirname(__FILE__) . "/../config.inc.php");
require_once(ModelEngineRoot . 'inc/modelengine.inc.php');

$id = isset($_POST['id']) ? $_POST['id'] : 0;

if($id > 0){
    $manager = $context->getBean('modelengine.business.form');
    $manager->copy($id);
    echo "1";
}else{
    echo "0";
}
?>
