<?php
require_once(dirname(__FILE__) . "/../config.inc.php");
require_once(ModelEngineRoot . 'inc/modelengine.inc.php');

$model = isset($_POST['model']) ? $_POST['model'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : 0;
$name = isset($_POST['name']) ? $_POST['name'] : '';
$value = isset($_POST['value']) ? $_POST['value'] : '';

if(strlen($model) > 0
    && $id > 0
    && strlen($name) > 0){
    $manager = $context->getBean('modelengine.business.model');
    $attributes = $manager->getAttributes($model);
    if(count($attributes) > 0){
        $attr = array();
        foreach($attributes as $attribute){
            if($name == $attribute['name']){
                $attribute['value'] = $value;
                array_push($attr, $attribute);
            }
            if($attribute['autoupdate'] || $attribute['primary']){
                array_push($attr, $attribute);
            }
        }
        $manager->save($model, $id, $attr);
        echo 1;
    }else{
        echo 0;
    }
}else{
    echo 0;
}
?>
