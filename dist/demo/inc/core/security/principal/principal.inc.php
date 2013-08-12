<?php
/*
* 上下文用户的标识对象接口。
*/
interface IIdentity{
	/**
	* 获取所使用的身份验证的类型。
	*/
	function getAuthenticationType();

	/**
	* 获取一个值，该值指示是否验证了用户。
	*/
	function isAuthenticated();

	/**
	* 获取当前用户的ID。
	*/
	function getUserId();

	/**
	* 获取当前用户的名字。
	*/
	function getName();
}

/**
* 上下文的用户对象接口。
*/
interface IPrincipal{
	/**
	* 获取当前用户的标识。
	*/
	function getIdentity();

	/**
	* 进行最初的拦截，进行认证。
	*/
	function intercept();

	/**
	* 确定当前用户是否属于指定的角色。
	* @param $role {String} 指定的角度。
	* @return {Boolean} 当前用户是否属于指定的角色。
	*/
	function isInRole($role);
}
?>