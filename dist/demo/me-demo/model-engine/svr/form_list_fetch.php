<?php
require_once(dirname(__FILE__) . "/../config.inc.php");
require_once(ModelEngineRoot . 'inc/modelengine.inc.php');

$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$model = isset($_GET['model']) ? $_GET['model'] : '';
$entities = array();
if (strlen($type) > 0 && $id > 0) {
    switch ($type) {
        case 'custom':
            $manager = $context->getBean('modelengine.business.customlistitem');
            //用户自定义属性值列表
            //实现了模型和表单引擎系统的ISystemListFetch接口的类
            $options = array('listId' => $id);
            $entities = $manager->fetchSystemList($options);
            break;
        case 'system':
            $manager = $context->getBean('modelengine.business.systemlist');
            $entity = $manager->getEntity($id);
            $clazz = $entity['clazz'];
            $system = $context->getBean($clazz);
            if ($system) {
                $options = array('code' => $model, 'editable' => true);
                $entities = $system->fetchSystemList($options);
            }
            break;
    }
}
echo json_encode($entities);
?>
