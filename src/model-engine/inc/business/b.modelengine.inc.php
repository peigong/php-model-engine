<?php
require_once(ROOT . 'inc/core/ioc/applicationcontext.inc.php');
require_once(ModelEngineRoot . 'inc/modelengine.inc.php');

/**
 * 模型引擎系统业务层模型类的接口。
 */
interface IBModel extends IInjectEnable, IModelListFetch{
    /**
     * 获取模型属性的列表。
     * @param $code {String} 模型的编码。
     * @param $editable {Boolean} 是否只列出可编辑属性。
     * @return 模型属性的列表。
     */
    function getAttributes($code, $editable = null);
    
    /**
     * 获取模型的数据实体。
     * @param $code {String} 模型的编码。
     * @return {mixed} 模型的数据实体。
     */
    function getEntityByCode($code);
    
    /**
     * 获取模型属性值的列表。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $attributes {Array} 模型属性数据。
     * 格式：
     * array(array(
     *       'id' => '',
     *       'name' => '',
     *       'comment' => '',
     *       'type' => '',
     *       'default' => '',
     *       'ext' => '',
     *       'editable' => '',
     *       'autoupdate' => '',
     *       'primary' => '',
     *       'position' => '',
     *       'value' => ''
     * ),... ...)
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return {Array} 模型属性值的列表。。
     */
    function getAttributeValues($code, $id, $attributes, $ext = array());
    
    /**
     * 获取模型属性值的列表。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $attributes {Array} 模型属性数据。
     * 格式：
     * array(array(
     *       'id' => '',
     *       'name' => '',
     *       'comment' => '',
     *       'type' => '',
     *       'default' => '',
     *       'ext' => '',
     *       'editable' => '',
     *       'autoupdate' => '',
     *       'primary' => '',
     *       'position' => '',
     *       'value' => ''
     * ),... ...)
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return {Array} 模型属性值的列表。。
     */
    function getAttributeValueList($code, $id, $attributes, $ext = array());
    
    /**
     * 添加一个新的模型。
     * @param $code {String} 模型的编码。
     * @param $attributes {Array} 模型属性数据。
     * 格式：
     * array(array(
     *       'id' => '',
     *       'name' => '',
     *       'comment' => '',
     *       'type' => '',
     *       'default' => '',
     *       'ext' => '',
     *       'editable' => '',
     *       'autoupdate' => '',
     *       'primary' => '',
     *       'position' => '',
     *       'value' => ''
     * ),... ...)
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return Int 新添加的模型ID（异常为-1）。
     */
    function create($code, $attributes, $ext = array());
    
    /**
     * 修改一个模型属性值。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $attributes {Array} 模型属性数据。
     * 格式：
     * array(array(
     *       'id' => '',
     *       'name' => '',
     *       'comment' => '',
     *       'type' => '',
     *       'default' => '',
     *       'ext' => '',
     *       'editable' => '',
     *       'autoupdate' => '',
     *       'primary' => '',
     *       'position' => '',
     *       'value' => ''
     * ),... ...)
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return Boolean 是否操作成功。
     */
    function save($code, $id, $attributes, $ext = array());
    
    /**
     * 删除一个模型。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
	 * @return Boolean 是否操作成功。
     * @param $ext {Array} 数据库切割需要的扩展参数。
     */
    function remove($code, $id, $ext = array());
    
    /**
     * 删除一个模型的属性值。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $attribute {Int} 模型属性的ID。
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return Boolean 是否操作成功。
     */
    function removeAttributeValue($code, $id, $attribute, $ext = array());
}

/**
 * 模型引擎系统业务层模型类别类的接口。
 */
interface IBModelCategory extends IInjectEnable, ISystemListFetch{
}

/**
 * 模型引擎系统业务层模型属性类的接口。
 */
interface IBAttribute extends IInjectEnable, IModelListFetch, ISystemListFetch{
    /**
     * 根据模型编码和属性名获取模型属性的数据实体。
     * @param $code {String} 模型的编码。
     * @param $field {String} 模型属性名。
     * @return Array 模型属性的数据实体。
     */
    function getEntity($code, $field);
}

/**
 * 模型引擎系统业务层模型表单类的接口。
 */
interface IBForm extends IInjectEnable, IModelListFetch{
    /**
     * 根据模型编码，获取模型表单的列表。
     * @param $code {String} 模型的编码。
     * @return 模型表单的列表。
     */
    function getListByModel($code);
    
    /**
     * 根据模型表单的ID，获取一个模型表单的配置数据。
     * @param $id {Int} 模型表单的ID。
     * @param $code {String} 模型的编码。
     * @return {mixed} 模型表单的配置数据。
     */
    function getModelFormById($id, $code = '');
    
    /**
     * 根据模型表单的名称，获取一个模型表单的配置数据。
     * @param $name {String} 模型表单的名称。
     * @param $code {String} 模型的编码。
     * @return {mixed} 模型表单的配置数据。
     */
    function getModelFormByName($name, $code = '');
    
    /**
     * 复制一个模型表单对象。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型表单对象的ID。
	 * @return Boolean 是否操作成功。
     */
    function copy($code, $id);
    
    /**
     * 删除一个模型表单对象。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型表单对象的ID。
	 * @return Boolean 是否操作成功。
     */
    function remove($code, $id);
}

/**
 * 模型引擎系统业务层模型表单属性类的接口。
 */
interface IBFormAttribute extends IInjectEnable, IModelListFetch{
    /**
     * 根据模型的编码和ID，获取模型表单的扩展属性列表。
     * @param $code {String} 模型的编码。
     * @param $modelId {int} 模型ID。
     * @return {Array} 模型表单的扩展属性列表。
     */
    function getAttributeValues($code, $modelId);
    
    /**
     * 根据模型的编码和ID，获取模型表单可编辑的内置和扩展属性列表。
     * @param $code {String} 模型的编码。
     * @param $modelId {int} 模型ID。
     * @return {Array} 模型表单可编辑的内置和扩展属性列表。
     */
    function getAttributeValueList($code, $modelId);
}

/**
 * 模型引擎系统业务层系统内置列表类的接口。
 */
interface IBSystemList extends IInjectEnable, ISystemListFetch{
    /**
     * 根据系统内置列表的ID，获取一个系统内置列表的数据实体。
     * @param $id {Int} 系统内置列表的ID。
     * @return {mixed} 系统内置列表的数据实体。
     */
    function getEntity($id);
}

/**
 * 模型引擎系统业务层用户自定义属性列表类的接口。
 */
interface IBCustomList extends IInjectEnable, IModelListFetch, ISystemListFetch{
}

/**
 * 模型引擎系统业务层用户自定义属性列表项类的接口。
 */
interface IBCustomListItem extends IInjectEnable, IModelListFetch, ISystemListFetch{
}

/**
 * 模型引擎系统业务层数据字段默认值类的接口。
 */
interface IBDefaultValue extends IInjectEnable, ISystemListFetch{
}

/**
 * 模型引擎系统业务层数据字段值类型类的接口。
 */
interface IBValueType extends IInjectEnable, ISystemListFetch{
}

/**
 * 模型引擎系统业务层模型表单对象验证类的接口。
 */
interface IBValidation extends IInjectEnable, IModelListFetch{
}
?>
