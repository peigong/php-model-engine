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
	 * @return {Int} 新添加的模型ID（异常为-1）。
     */
    function create($code, $attributes, $ext = array());

    /**
     * 向数据库插入模型数据。
     * @param $code {String} 模型的编码。
     * @param $name {String} 模型的名称。
     * @param $description {String} 模型的描述信息。
     * @param $model {String} 模型数据表。
     * @param $attribute {String} 模型扩展属性数据表。
     * @param $category {Int} 模型的类别ID。
     * @return {Int} 新增数据的ID。
     */
    function add($code, $name, $description, $model, $attribute, $category);
    
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
	 * @return {Boolean} 是否操作成功。
     */
    function save($code, $id, $attributes, $ext = array());
    
    /**
     * 删除一个模型。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return {Boolean} 是否操作成功。
     */
    function remove($code, $id, $ext = array());
    
    /**
     * 删除一个模型的属性值。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型数据的ID。
     * @param $attribute {Int} 模型属性的ID。
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return {Boolean} 是否操作成功。
     */
    function removeAttributeValue($code, $id, $attribute, $ext = array());
}

/**
 * 模型引擎系统业务层模型类别类的接口。
 */
interface IBModelCategory extends IInjectEnable, IModelListFetch, ISystemListFetch{
    /**
     * 向数据库插入模型类别数据。
     * @param $name {String} 模型类别名称。
     * @param $description {String} 模型类别的描述信息。
     * @return {Int} 新增数据的ID。
     */
    function add($name, $description);
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

    /**
     * 向数据库插入模型属性数据。
     * @param $name {String} 模型属性名称。
     * @param $comment {String} 模型属性的注释。
     * @param $type {String} 模型属性值的类型。
     * @param $default {String} 模型属性的默认值。
     * @param $model {String} 模型的编码。
     * @param $list {Int} 模型属性值可选列表的ID（<0：系统内置列表；>0：用户自定义列表。）。
     * @param $ext {Int} 是否是扩展属性。
     * @param $editable {Int} 是否是允许用户编辑的属性。
     * @param $autoupdate {Int} 在修改数据时，是否使用默认值自动更新。
     * @param $primary {Int} 是否是主键属性。
     * @param $position {Int} 用于排序的值。
     * @param $category {String} 模型属性类别的自增ID。
     * @return {Int} 新增数据的ID。
     */
    function add($name, $comment, $type, $default, $model, $list, $ext, $editable, $autoupdate, $primary, $position, $category);
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
     * 根据上级ID，获取模型表单的数据列表。
     * @param $parentId {int} 上级ID。
     * @return {Array} 模型表单的数据列表。
     */
    function getListByParentId($parentId);
    
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
     * 复制一个模型表单。
     * @param $id {Int} 模型表单的ID。
     */
    function copy($id);

    /**
    * 装载一个表单对象的子对象及表单验证对象等数据。
    * @param $form {Array} 待装载的表单初始数据对象。
    * @return {Array} 表单完整数据对象。
    */
    function load_form_data($form);

    /**
    * 向数据库导入一个表单完整数据对象。
    * @param $form {Array} 待导入的表单完整数据对象。
    * @param $parent {Array} 表单对象的父ID。
    */
    function import_form_data($form, $parent);
    
    /**
     * 删除一个模型表单对象。
     * @param $code {String} 模型的编码。
     * @param $id {Int} 模型表单对象的ID。
	 * @return {Boolean} 是否操作成功。
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

    /**
    * 增加系统内置列表。
    * @param $name {String} 列表的名称。
    * @param $description {String} 列表的描述信息。
    * @param $clazz {String} 列表实现类的IOC编号。
    * @param $position {Int} 排序权重。
    */
    function add($name, $description, $clazz, $position);
}

/**
 * 模型引擎系统业务层用户自定义属性列表类的接口。
 */
interface IBCustomList extends IInjectEnable, IModelListFetch, ISystemListFetch{
    /**
    * 增加用户自定义属性列表。
    * @param $name {String} 列表的名称。
    * @param $description {String} 列表的描述信息。
    * @param $position {Int} 排序权重。
    */
    function add($name, $description, $position);
}

/**
 * 模型引擎系统业务层用户自定义属性列表项类的接口。
 */
interface IBCustomListItem extends IInjectEnable, IModelListFetch, ISystemListFetch{
    /**
    * 增加用户自定义属性列表项。
    * @param $list {Int} 所属列表的编号。
    * @param $value {String} 列表项的值。
    * @param $text {String} 列表项的文本。
    * @param $position {Int} 排序权重。
    */
    function add($list, $value, $text, $position);
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

/**
 * 模型引擎系统业务层工具类的接口。
 */
interface IBModelTool{
    /**
    * 获取系统中的模型列表。
    */
    function get_models();

    /**
    * 获取系统中的模型类别列表。
    */
    function get_model_categories();

    /**
    * 获取系统内置属性值下拉列表。
    */
    function get_system_lists();

    /**
    * 获取用户自定义属性值可选列表。
    */
    function get_custom_lists();

    /**
    * 具备模型定义数据的系统模块。
    */
    function get_mdd_modules();

    /**
    * 导出模型的数据。
    * @param $codes {Array} 需要导出的模型编码列表。
    */
    function export_models($codes);

    /**
    * 导出模型类别的数据。
    * @param $ids {Array} 需要导出的模型类别编号的列表。
    */
    function export_categories($ids);

    /**
    * 导出系统内置属性值下拉列表的数据。
    * @param $ids {Array} 需要导出的模型类别编号的列表。
    */ 
    function export_system_lists($ids);
    
    /**
    * 导出用户自定义属性值可选列表的数据。
    * @param $ids {Array} 需要导出的模型类别编号的列表。
    */
    function export_custom_lists($ids);

    /**
    * 导出模型和表单引擎系统中的所有表单对象配置。
    */
    function export_forms($target);

    /**
    * 导入系统模块的模型定义数据。
    * @param $modules {Array} 需要导入的系统模块列表。
    */
    function import_modules($modules);

    /**
    * 获取已经导出了的JSON文件列表。
    */
    function get_export_files();
}
?>
