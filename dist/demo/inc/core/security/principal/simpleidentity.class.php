<?php
/*
* 简易的上下文用户的标识对象。
*/
class SimpleIdentity implements IIdentity{
	/**
	* 获取所使用的身份验证的类型。
	*/
	public function getAuthenticationType(){
		return "";
	}

	/**
	* 获取一个值，该值指示是否验证了用户。
	*/
	public function isAuthenticated(){
		return false;
	}

	/**
	* 获取当前用户的ID。
	*/
	public function getUserId(){
		return 0;
	}

	/**
	* 获取当前用户的名字。
	*/
	public function getName(){
		return "";
	}
}
?>