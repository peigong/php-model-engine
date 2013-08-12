<?php
/**
 * 模型枚举。
 */
define('MODEL_TYPE_MODEL', 'model');
define('MODEL_TYPE_ATTRIBUTE', 'attribute');
define('MODEL_TYPE_SYSTEMLIST', 'systemlist');
define('MODEL_TYPE_CUSTOMLIST', 'customlist');
define('MODEL_TYPE_CUSTOMLISTITEM', 'customlistitem');
define('MODEL_TYPE_MODELFORM', 'modelform');
define('MODEL_TYPE_TABCONTAINER', 'tabcontainer');
define('MODEL_TYPE_ROWCONTAINER', 'rowcontainer');
define('MODEL_TYPE_TEXTINPUT', 'textinput');
define('MODEL_TYPE_SELECTINPUT', 'selectinput');
define('MODEL_TYPE_CHECKBOXINPUT', 'checkboxinput');
define('MODEL_TYPE_CHECKBOXLISTINPUT', 'checkboxlistinput');
define('MODEL_TYPE_RADIOLISTINPUT', 'radiolistinput');
define('MODEL_TYPE_FILEINPUT', 'fileinput');
define('MODEL_TYPE_VALIDATION', 'validation');

/**
 * 模型列表获取服务的窄接口。
 */
interface IModelListFetch{
    /**
     * 获取模型列表。
     * @param $code {String || Int} 用于获取列表的条件编码。
     * @param $ext {Array} 扩展的附加条件字典。
     * @return 模型列表。
     */
    function fetchModelList($code, $ext);
}

/**
 * 系统内置列表获取服务的窄接口。
 */
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
?>
