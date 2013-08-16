<?php
/**
 * 模型枚举。
 */
if (!defined('MODEL_TYPE_MODEL')) {
    define('MODEL_TYPE_MODEL', 'model');
}
if (!defined('MODEL_TYPE_ATTRIBUTE')) {
    define('MODEL_TYPE_ATTRIBUTE', 'attribute');
}
if (!defined('MODEL_TYPE_SYSTEMLIST')) {
    define('MODEL_TYPE_SYSTEMLIST', 'systemlist');
}
if (!defined('MODEL_TYPE_CUSTOMLIST')) {
    define('MODEL_TYPE_CUSTOMLIST', 'customlist');
}
if (!defined('MODEL_TYPE_CUSTOMLISTITEM')) {
    define('MODEL_TYPE_CUSTOMLISTITEM', 'customlistitem');
}
if (!defined('MODEL_TYPE_MODELFORM')) {
    define('MODEL_TYPE_MODELFORM', 'modelform');
}
if (!defined('MODEL_TYPE_TABCONTAINER')) {
    define('MODEL_TYPE_TABCONTAINER', 'tabcontainer');
}
if (!defined('MODEL_TYPE_ROWCONTAINER')) {
    define('MODEL_TYPE_ROWCONTAINER', 'rowcontainer');
}
if (!defined('MODEL_TYPE_TEXTINPUT')) {
    define('MODEL_TYPE_TEXTINPUT', 'textinput');
}
if (!defined('MODEL_TYPE_SELECTINPUT')) {
    define('MODEL_TYPE_SELECTINPUT', 'selectinput');
}
if (!defined('MODEL_TYPE_CHECKBOXINPUT')) {
    define('MODEL_TYPE_CHECKBOXINPUT', 'checkboxinput');
}
if (!defined('MODEL_TYPE_CHECKBOXLISTINPUT')) {
    define('MODEL_TYPE_CHECKBOXLISTINPUT', 'checkboxlistinput');
}
if (!defined('MODEL_TYPE_RADIOLISTINPUT')) {
    define('MODEL_TYPE_RADIOLISTINPUT', 'radiolistinput');
}
if (!defined('MODEL_TYPE_FILEINPUT')) {
    define('MODEL_TYPE_FILEINPUT', 'fileinput');
}
if (!defined('MODEL_TYPE_VALIDATION')) {
    define('MODEL_TYPE_VALIDATION', 'validation');
}

/**
 * 模型列表获取服务的窄接口。
 */
if (!interface_exists('IModelListFetch')) {
    interface IModelListFetch{
        /**
         * 获取模型列表。
         * @param $code {String || Int} 用于获取列表的条件编码。
         * @param $ext {Array} 扩展的附加条件字典。
         * @return 模型列表。
         */
        function fetchModelList($code, $ext);
    }
}

/**
 * 系统内置列表获取服务的窄接口。
 */
if (!interface_exists('ISystemListFetch')) {
    interface ISystemListFetch{
        /**
         * 获取系统内置列表。
         * @param $options {Array} 可选项。
         * @return 系统内置列表。
         * 数据格式：array(
         *   array('text' => '', 'value' => '')
         *   )
         */
        function fetchSystemList($options);
    }
}
?>
