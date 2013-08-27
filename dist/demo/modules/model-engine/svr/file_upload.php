<?php
require_once(dirname(__FILE__) . "/../config.inc.php");

$handler = $context->getBean('modelengine.global.uploadhandler');
$handler->context = $httpContext;
$handler->receive();
?>
