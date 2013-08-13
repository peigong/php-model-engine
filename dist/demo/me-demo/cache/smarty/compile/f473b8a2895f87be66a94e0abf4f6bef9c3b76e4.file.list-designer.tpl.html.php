<?php /* Smarty version Smarty-3.1-DEV, created on 2013-08-13 11:17:02
         compiled from ".\templates\list-designer.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:4220520a15ae33aef5-36456695%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f473b8a2895f87be66a94e0abf4f6bef9c3b76e4' => 
    array (
      0 => '.\\templates\\list-designer.tpl.html',
      1 => 1376392619,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4220520a15ae33aef5-36456695',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_520a15ae3ae560_62400007',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520a15ae3ae560_62400007')) {function content_520a15ae3ae560_62400007($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/header.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<script type="text/javascript" src="../js/config.js"></script>
<style type="text/css">
</style>
<section class="section">
    <div class="container">
        <h1>模型和模型表单设计</h1>
        <!-- 二级导航 -->
        <ul class="nav nav-tabs">
            <li><a href="designer.php?type=form">表单设计</a></li>
            <li><a href="designer.php?type=model">模型设计</a></li>
            <li class="active"><a href="designer.php?type=list">属性列表</a></li>
        </ul>
        <aside class="left col-3">
            <h3 class="title">系统内置列表<span class="model-ctrl"><a id="systemlist_create_handler" title="添加系统内置列表" href="#"><i class="icon-plus"></i></a></span></h3>
            <div id="systemlist_container"></div>
        </aside>
        <aside class="list col-3">
            <h3 class="title">用户自定义列表<span class="model-ctrl"><a id="customlist_create_handler" title="添加用户自定义列表" href="#"><i class="icon-plus"></i></a></span></h3>
            <div id="customlist_container"></div>
        </aside>
        <aside class="list col-4">
            <h3 class="title">用户自定义列表项<span class="model-ctrl"><a id="customlistitem_create_handler" title="添加用户自定义列表项" href="#"><i class="icon-plus"></i></a></span></h3>
            <div id="customlistitem_container"></div>
        </aside>
    </div>
</section>
<script type="text/javascript">
seajs.use([
'model-engine/js/enum', 
'model-engine/js/modelctrllist', 
'model-engine/js/linkagelist'
], function(enu, mcl, ll){
    var ModelType = enu.ModelType,
        ModelCtrlList = mcl.ModelCtrlList,
        LinkageList = ll.LinkageList;
    var systemlist = new ModelCtrlList("#systemlist_container", ModelType.SYSTEMLIST, { 
            'value': 'list_id', 
            'text': 'list_name',
            'name': 'list_name', 

            'add': {
                'handler': '#systemlist_create_handler', 
                'form_name': 'frm_model_systemlist_create'
            },

            'edit': {
                'key': 'list_id',
                'form_name': 'frm_model_systemlist_update'
            },

            'delete': {
                'service': '/model-engine/svr/model_delete.php',
                'options' : { 'model': ModelType.SYSTEMLIST, 'id': '{ list_id }' }             
            }
        } ),
        customlist = new ModelCtrlList("#customlist_container", ModelType.CUSTOMLIST, { 
            'value': 'list_id', 
            'text': 'list_name',
            'name': 'list_name', 

            'add': {
                'handler': '#customlist_create_handler', 
                'form_name': 'frm_model_customlist_create'
            },

            'edit': {
                'key': 'list_id',
                'form_name': 'frm_model_customlist_update'
            },

            'delete': {
                'service': '/model-engine/svr/model_delete.php',
                'options' : { 'model': ModelType.CUSTOMLIST, 'id': '{ list_id }' }             
            } 
        } ),
        customlistitem = new ModelCtrlList("#customlistitem_container", ModelType.CUSTOMLISTITEM, { 
            'value': 'item_id', 
            'text': '[{ item_value }]{ item_text }',
            'name': 'item_text', 

            'add': {
                'handler': '#customlistitem_create_handler', 
                'form_name': 'frm_model_customlistitem_create',
                'options': { 'list_id': 'code' }
            },

            'edit': {
                'key': 'item_id',
                'form_name': 'frm_model_customlistitem_update'
            },

            'delete': {
                'service': '/model-engine/svr/model_delete.php',
                'options' : { 'model': ModelType.CUSTOMLISTITEM, 'id': '{ item_id }' }             
            }
        } ),
        custom_linkage = new LinkageList(customlist, customlistitem);
    systemlist.load();
    custom_linkage.load();
} );
</script>
<?php echo $_smarty_tpl->getSubTemplate ("common/footer.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>