<?php
require_once(dirname(__FILE__) . "/config.inc.php");

$page_id = 'modelengine.pages.modeldesignerpage';
$page = $context->getBean($page_id);
$type = isset($_GET['type']) ? $_GET['type'] : 'form';
$page->type = $type;
$page->render();
?>