<?php
require_once(ROOT . "inc/core/httpcontext.class.php");

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
    private $menu = array();
    private $menuProvider = null;
    private $authProvider = null;

    /**
     * 构造函数。
     */
    function  __construct(){
        /*设置上下文状态*/
        global $context, $httpContext;
        if (!$httpContext) {
            $httpContext = new HttpContext($context);
        }
        $this->user = $httpContext->getUser();
        $this->identity = $this->user->getIdentity();
        if ($this->identity) {
            $this->userName = $this->identity->getName();
        }
        if ($context) {
            $this->menuProvider = $context->getBean("site.menu.provider");
            $this->authProvider = $context->getBean("security.authorization.provider");
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
        $permissions = $this->user->getPermissions();
        if ($this->authProvider) {
            $this->authProvider->intercept($permissions);
        }
        if($tpl){
            /*- 当前登录用户的名称 -*/
            $this->assign('UserName', $this->userName);
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
            /*- 页面菜单的配置 -*/
            if ($this->menuProvider) {
                $this->menu = $this->menuProvider->getMenuSettings($permissions, $this->menu);
            }
            $this->assign('Menu', $this->menu);
            /*配置常用常量*/
            if (defined('STATIC_HOST')) {
                $this->assign('StaticHost', STATIC_HOST);
            }
            if (defined('VirtualModelEngineRoot')) {
                $this->assign('VirtualModelEngineRoot', VirtualModelEngineRoot);
            }
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
     * 设置当前状态的菜单项。
     * @param $menu {String} 页面上的菜单标识。
     * @param $item {String} 页面上的菜单项标识。
     */
    public function setMenuActive($menu, $item){
        $this->menu = array($menu => array($item => array('active' => 'active')));
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