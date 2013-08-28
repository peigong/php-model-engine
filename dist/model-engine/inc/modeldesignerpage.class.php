<?php
require_once(ROOT . 'inc/core/webpage.class.php');
require_once(ROOT . 'inc/core/ioc/applicationcontext.inc.php');

/**
 * 模型设计器页面类。
 */
class ModelDesignerPage extends WebPage implements IInjectEnable {
    private $type = 'form';
    private $templates = array(
        'form' => array('title' => '表单设计-工作台', 'template' => 'form-designer.tpl.html'),
        'model' => array('title' => '模型设计-工作台', 'template' => 'model-designer.tpl.html'),
        'list' => array('title' => '属性列表设计-工作台', 'template' => 'list-designer.tpl.html')
    );

    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
    
    /**
     * 渲染页面。
     */
    public function render(){  
        if(array_key_exists($this->type, $this->templates)){
            $template = $this->templates[$this->type];
            $title = $template['title'];
            $temp = $template['template'];
            $this->setTitle($title);
            $this->setMenuActive('model-engine-sub', $this->type);
            $this->smarty->addTemplateDir(ModelEngineRoot . 'templates');
            if (defined('STATIC_HOST')) {
                $this->assign('StaticHost', STATIC_HOST);
            }
            $this->display($temp);
        }
    }
}
?>
