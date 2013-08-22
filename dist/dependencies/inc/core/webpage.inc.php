<?php
/**
 * 提供菜单服务的对象接口。
 */
interface IMenuProvider extends IInjectEnable{    
	/**
	* 提供用于页面模板的菜单控制的配置信息。
	* @param $permissions {Array} 当前用户所拥有的许可列表。
	* @param $settings {Array} 当前已有的菜单配置信息。
	*/
	function getMenuSettings($permissions, $settings);
}

/**
* 提供安全验证服务的对象接口。
*/
interface IAuthorizationProvider extends IInjectEnable{
	/**
	* 进行最初的拦截，进行认证。
	* @param $permissions {Array} 当前用户所拥有的许可列表。
	*/
	function intercept($permissions);
}
?>
