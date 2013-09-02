<?php
require_once(ROOT . 'inc/core/ioc/applicationcontext.inc.php');
require_once(ModelEngineRoot . 'inc/modelengine.inc.php');

/*- 数据库字段默认值常量 -*/
/**
 * 主键类自增ID。
 */
define('DEFAULT_VALUE_AUTOINCREMENT', 'autoincrement');
/**
 * 空字符串。
 */
define('DEFAULT_VALUE_EMPTY', 'empty');
/**
 * 当前时间戳数字表示。
 */
define('DEFAULT_VALUE_NOW', 'now');
/**
 * 数字零。
 */
define('DEFAULT_VALUE_ZERO', 'zero');

/*- 扩展属性值的类型常量 -*/
/**
 * 数字类型的属性值。
 */
define('VALUE_TYPE_INT', 'int');
/**
 * 布尔类型的属性值。
 */
define('VALUE_TYPE_BOOL', 'bool');
/**
 * 文本类型的属性值。
 */
define('VALUE_TYPE_STR', 'str');

/**
 * 模型引擎系统数据层模型类的接口。
 */
interface IDModel extends IInjectEnable{
    /**
     * 获取模型的列表。
     * @param $cid {Int} 模型类别的ID。
     * @return {Array} 模型的列表。
     */
    function getList($cid);
    
    /**
     * 获取模型的数据实体。
     * @param $code {String} 模型的编码。
     * @return {mixed} 模型的数据实体。
     */
    function getEntityByCode($code);
    
    /**
     * 获取模型内置属性值的列表。
     * @param $id {Int} 模型数据的ID。
     * @param $table {String} 模型数据存储的数据表。
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
	 * @return {Array} 模型内置属性值的列表。。
     */
    function getAttributeValues($id, $table, $attributes, $ext = array());
    
    /**
     * 获取模型内置属性值的列表。
     * @param $id {Int} 模型数据的ID。
     * @param $table {String} 模型数据存储的数据表。
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
	 * @return {Array} 模型内置属性值的列表。。
     */
    function getAttributeValueList($id, $table, $attributes, $ext = array());
    
    /**
     * 添加一个新的模型。
     * @param $table {String} 模型数据存储的数据表。
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
    function create($table, $attributes, $ext);

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
     * @param $id {Int} 模型数据的ID。
     * @param $table {String} 模型数据存储的数据表。
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
    function save($id, $table, $attributes, $ext);
    
    /**
     * 删除一个模型。
     * @param $id {Int} 模型数据的ID。
     * @param $table {String} 模型数据存储的数据表。
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
    function remove($id, $table, $attributes, $ext);
}

/**
 * 模型引擎系统数据层模型类别类的接口。
 */
interface IDModelCategory extends IInjectEnable{
    /**
     * 向数据库插入模型类别数据。
     * @param $name {String} 模型类别名称。
     * @param $description {String} 模型类别的描述信息。
     * @return {Int} 新增数据的ID。
     */
    function add($name, $description);
    
    /**
     * 获取模型类别的列表。
     * @return {Array} 模型类别的列表。
     */
    function getList();
}

/**
 * 模型引擎系统数据层模型属性类的接口。
 */
interface IDAttribute extends IInjectEnable{
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
    
    /**
     * 获取模型属性的列表。
     * @param $code {String} 模型的编码。
     * @param $editable {Boolean} 是否只列出可编辑属性。
     * @return 模型属性的列表。
     */
    function getList($code, $editable = null);
    
    /**
     * 根据模型编码和属性名获取模型属性的数据实体。
     * @param $code {String} 模型的编码。
     * @param $field {String} 模型属性名。
     * @return {Array} 模型属性的数据实体。
     */
    function getEntity($code, $field);
    
    /**
     * 获取模型扩展属性值的列表。
     * @param $modelId {Int} 模型数据的ID。
     * @param $table {String} 模型扩展属性存储的数据表。
	 * @return {Array} 模型扩展属性值的列表。。
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
     */
    function getAttributeValues($modelId, $table, $attributes);
    
    /**
     * 根据模型ID，获取模型扩展属性的属性值列表。
     * @param $modelId {Int} 模型数据的ID。
     * @param $table {String} 模型扩展属性存储的数据表。
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
     * @return {Array} 模型表单的属性列表。
     */
    function getAttributeValueList($modelId, $table, $attributes);
    
    /**
     * 创建模型的扩展属性。
     * @param $code {String} 模型的编码。
     * @param $modelId {Int} 模型数据的ID。
     * @param $table {String} 模型扩展属性存储的数据表。
     * @param $attributes {Array} 模型扩展属性数据。
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
    */
    function create($code, $modelId, $table, $attributes, $ext = array());
    
    /**
     * 修改一个模型的扩展属性值。
     * @param $code {String} 模型的编码。
     * @param $modelId {Int} 模型数据的ID。
     * @param $table {String} 模型扩展属性存储的数据表。
     * @param $attributes {Array} 模型扩展属性数据。
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
    function save($code, $modelId, $table, $attributes, $ext = array());
    
    /**
     * 删除一个模型的扩展属性值。
     * @param $code {String} 模型的编码。
     * @param $modelId {Int} 模型数据的ID。
     * @param $table {String} 模型扩展属性存储的数据表。
     * @param $attribute {Int} 模型属性的ID。
     * @param $ext {Array} 数据库切割需要的扩展参数。
	 * @return {Boolean} 是否操作成功。
     */
    function removeValue($code, $modelId, $table, $attribute = -1, $ext = array());

    /**
    * 重置模型属性关联表中的扩展属性值的ID。
    * @param $table {String} 模型属性关联表。
    * @param $attributes {Array} 新旧扩展属性值ID的对照表。
    * 数据格式如：array(array('old' => '', 'new' => ''))
    */
    function reset($table, $attributes);
}

/**
 * 模型引擎系统数据层模型表单类的接口。
 */
interface IDForm extends IInjectEnable{
    /**
     * 获取模型表单的数据实体。
     * @param $id {Int} 模型表单的ID。
     * @return {mixed} 模型表单的数据实体。
     */
    function getEntityById($id);
    
    /**
     * 获取模型表单的数据实体。
     * @param $name {String} 模型表单的名称。
     * @return {mixed} 模型表单的数据实体。
     */
    function getEntityByName($name);
    
    /**
     * 根据上级ID，获取模型表单的数据列表。
     * @param $parentId {int} 上级ID。
     * @return {Array} 模型表单的数据列表。
     */
    function getListByParentId($parentId);
    
    /**
     * 根据模型编码，获取模型表单的列表。
     * @param $code {String} 模型的的编码。
     * @return 模型表单的列表。
     */
    function getListByModel($code);
}

/**
 * 模型引擎系统数据层系统内置列表类的接口。
 */
interface IDSystemList extends IInjectEnable{
    /**
     * 根据系统内置列表的ID，获取一个系统内置列表的数据实体。
     * @param $id {Int} 系统内置列表的ID。
     * @return {mixed} 系统内置列表的数据实体。
     */
    function getEntity($id);
    
    /**
     * 获取系统内置列表。
     * @return {Array} 系统内置列表
     */
    function getList();

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
 * 模型引擎系统数据层用户自定义列表类的接口。
 */
interface IDCustomList extends IInjectEnable{
    /**
     * 获取用户自定义属性值可选列表。
     * @return {Array} 属性值可选列表。
     */
    function getList();

    /**
    * 增加用户自定义属性列表。
    * @param $name {String} 列表的名称。
    * @param $description {String} 列表的描述信息。
    * @param $position {Int} 排序权重。
    */
    function add($name, $description, $position);
}

/**
 * 模型引擎系统数据层用户自定义列表项类的接口。
 */
interface IDCustomListItem extends IInjectEnable{    
    /**
     * 获取属性值可选列表的列表项。
     * @param $listId Int 列表编号。
     * @return {Array} 可选列表的列表项。
     */
    function getListItems($listId);
    
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
 * 模型引擎系统数据层数据字段默认值类的接口。
 */
interface IDDefaultValue extends IInjectEnable{
    /**
     * 获取数据字段默认值的列表。
     * @return {Array} 数据字段默认值的列表。
     */
    function getList();
}

/**
 * 模型引擎系统数据层数据字段值类型类的接口。
 */
interface IDValueType extends IInjectEnable{
    /**
     * 获取数据字段值类型的列表。
     * @return {Array} 数据字段值类型的列表。
     */
    function getList();
}

/**
 * 模型引擎系统数据层模型表单对象验证类的接口。
 */
interface IDValidation extends IInjectEnable{
    /**
     * 获取模型表单对象验证方法的列表。
     * @param $formId {int} 表单对象的ID。
     * @return {Array} 模型表单对象验证方法的列表。
     */
    function getList($formId);
}
?>
