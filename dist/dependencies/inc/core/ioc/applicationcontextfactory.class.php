<?php
require_once(ROOT . "inc/core/ioc/applicationcontext.inc.php");
require_once(ROOT . "inc/core/ioc/filesystemxmlapplicationcontext.class.php");

/**
 * 应用程序上下文对象的工厂。
 */
class ApplicationContextFactory{
    private static $factory = null;
    private $context = null;
    
    /**
     * 获取单例模式的工厂实例。
     * @return ApplicationContextFactory 缓存机制工厂的实例。
     */
    public static function getIntance(){
        if(self::$factory == null){
            self::$factory = new self();
        }
        return self::$factory;
    }
    
    /**
     * 创建应用程序上下文对象。
     * @return IApplicationContext 应用程序上下文对象。
     */
    public function create(){
        if($this->context == null){
            $this->context = new FileSystemXmlApplicationContext();
        }
        return $this->context;
    } 
}
?>
