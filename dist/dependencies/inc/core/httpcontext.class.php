<?php
/**
* HTTP上下文对象。
*/
class HttpContext
{
    private static $current = null;
    private $context = null;
    private $principal = null;
    
    
    /**
     * 构造函数。
     */
    function  __construct($context = null){
        $this->context = $context;
    }
    
    /**
     * 构造函数。
     */
    function HttpContext($context = null){
        $this->__construct($context);
    }

    /**
     * 获取HTTP上下文对象。
     * @return HttpContext HTTP上下文对象。
     */
    public static function getCurrent($context){
        if(self::$current == null){
            self::$current = new self($context);
        }
        return self::$current;
    }

    /**
    * 为当前 HTTP 请求获取安全信息。
    * @return {IPrincipal} 实现IPrincipal接口的类。
    */
    public function getUser(){
        if (null == $this->principal) {
            if ($this->context) {
                $this->principal = $this->context->getBean("security.principal");
            }
            if (null == $this->principal) {
                require_once(ROOT . 'inc/core/security/principal/simpleprincipal.class.php');
                $this->principal = new SimplePrincipal();
            }
        }
        return $this->principal;
    }

    /**
    * 为当前 HTTP 请求获取安全信息。
    * @param {IPrincipal} $principal 实现IPrincipal接口的类。
    */
    public function setPrincipal($principal){
        $this->principal = $principal;
    }
}
?>
