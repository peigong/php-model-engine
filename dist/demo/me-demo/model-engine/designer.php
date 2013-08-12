<?php
/**
 * 广告前端系统统一DEMO项目
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */
require_once(dirname(__FILE__) . "/config.inc.php");

$page_id = 'modelengine.pages.modeldesignerpage';
$page = $context->getBean($page_id);
$type = isset($_GET['type']) ? $_GET['type'] : 'form';
$page->type = $type;
$page->render();
?>