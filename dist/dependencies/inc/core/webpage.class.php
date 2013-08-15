<?php
require_once(ROOT . "inc/core/httpcontext.class.php");

/**
 * 广告前端系统统一DEMO项目
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */
require_once(SMARTY_PATH . 'Smarty.class.php');

/**
 * 网站页面的基类。
 */
abstract class WebPage{
    /**
     * 渲染页面。
     */
    abstract public function render();
    
    /**
     * 模板引擎。
     */
    protected $smarty;

    /**
    * 用户
    */
    protected $user;
    protected $identity;
    protected $userName = '';

    private $title = '';

    /**
     * 构造函数。
     */
    function  __construct(){
        /*设置上下文状态*/
        global $context;
        $httpContext = new HttpContext($context);
        $this->user = $httpContext->getUser();
        $this->user->intercept();
        $this->identity = $this->user->getIdentity();
        if ($this->identity) {
            $this->userName = $this->identity->getName();
        }

        /*设置Smarty模板对象*/
        $this->smarty = new Smarty();
        $this->smarty->addTemplateDir(SMARTY_TEMPLATES);
        $this->smarty->compile_dir = SMARTY_CACHE . 'compile';
        $this->smarty->cache_dir = SMARTY_CACHE . 'cache';
        $this->smarty->compile_locking = false;
     }
    
    /**
     * 构造函数。
     */
    function WebPage(){
        $this->__construct();
    }
    
    /**
     * 渲染页面。
     * @param $tpl {String} 模板地址（基于全局模板目录的）。
     */
    protected function display($tpl){
        if($tpl){
            /*- 定义系统名称和页面标题 -*/
            $sys_name = '';
            if (defined('SYSTEMNAME')) {
                $sys_name = SYSTEMNAME;
            }
            $this->assign('SYSTEMNAME', $sys_name);
            if(strlen(trim($this->title)) > 0 && $sys_name){

                $this->title .= ('-' . $sys_name);
            }else if($sys_name){
                $this->title = $sys_name;
            }
            $this->assign('PageTile', $this->title);
            $this->assign('UserName', $this->userName);
            @$this->smarty->display($tpl);
        }
    }
    
    /**
     * 赋值方法。
     * @param $key {String} 模板变量名。
     * @param $val {Object} 模板变量值。
     */
    public function assign($key, $val){
        $this->smarty->assign($key, $val);
    }
    
    /**
     * 设置网页的副标题。
     * @param $title {String} 副标题。
     */
    public function setTitle($title = ''){
        $this->title = $title;
    }

    /**
    * 确定当前用户是否属于指定的角色。
    * @param $role {String} 指定的角度。
    * @return {Boolean} 当前用户是否属于指定的角色。
    */
    public function isInRole($role){
        $result = false;
        if ($this->user) {
            $result = $this->user->isInRole($role);
        }
        return $result;
    }

    /**
    * 确定当前用户是否拥有指定的许可。
    * @param $permission {String} 指定的许可。
    * @return {Boolean} 当前用户是否拥有指定的许可。
    */
    public function hasPermission($permission){
        $result = false;
        if ($this->user) {
            $result = $this->user->hasPermission($permission);
        }
        return $result;
    }
}
?>