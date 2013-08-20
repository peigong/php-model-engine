<?php
require_once(dirname(__FILE__) . "/../config.inc.php");
require_once(ModelEngineRoot . 'inc/modelengine.inc.php');

/*-- 表单要求执行的Action的类型 --*/
define('FORM_ACTION_NONE', 'none');
define('FORM_ACTION_ADD', 'add');
define('FORM_ACTION_UPDATE', 'update');

$identity = $httpContext->getUser()->getIdentity();
$uid = $identity->getUserId();
$ext = array('uid' => $uid);

$type = isset($_POST['t']) ? $_POST['t'] : '';

if(strlen($type) > 0){
    $manager = $context->getBean('modelengine.business.model');
    $attributes = $manager->getAttributes($type);
    if(count($attributes) > 0){
        $id = 0;
        $action = FORM_ACTION_ADD;
        $retainable_attributes = array();
        $abandonable_attributes = array();

        foreach($attributes as $attribute){
            if (array_key_exists($attribute['name'], $_POST)) {
                $value = $_POST[$attribute['name']];
                $value = isset($value) ? $value : '';
                $attribute['value'] = $value;
                array_push($retainable_attributes, $attribute);

                if($attribute['primary']){//如果传递了主键参数
                    //有参数值执行更新操作，没有参数值不执行任何操作
                    if ($value && $value > 0) {//假定为数字主键
                        $id = $value;
                        $action = FORM_ACTION_UPDATE;
                    }else{
                        $action = FORM_ACTION_NONE;
                    }
                }
            }else if($attribute['autoupdate']){// 表单没有提交该属性
                array_push($retainable_attributes, $attribute);
            }else{
                $attribute['value'] = '';
                array_push($abandonable_attributes, $attribute);                
            }
        }

        switch ($action) {
            case FORM_ACTION_ADD:
                foreach ($abandonable_attributes as $idx => $attribute) {
                    array_push($retainable_attributes, $attribute);
                }
                $manager->create($type, $retainable_attributes, $ext);
                echo 1;
                break;
            case FORM_ACTION_UPDATE:
                $manager->save($type, $id, $retainable_attributes, $ext);
                echo 1;
                break;
            case FORM_ACTION_NONE:
            default:
                echo 0;
        }
    }else{
        echo 0;
    }
}else{
    echo 0;
}
?>
