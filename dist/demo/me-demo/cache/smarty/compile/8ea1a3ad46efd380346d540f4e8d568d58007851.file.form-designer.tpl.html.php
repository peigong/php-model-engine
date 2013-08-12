<?php /* Smarty version Smarty-3.1-DEV, created on 2013-08-12 11:25:19
         compiled from ".\templates\form-designer.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:215915208c61f4f9581-01649294%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8ea1a3ad46efd380346d540f4e8d568d58007851' => 
    array (
      0 => '.\\templates\\form-designer.tpl.html',
      1 => 1376306713,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '215915208c61f4f9581-01649294',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_5208c61f5913e2_31718523',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5208c61f5913e2_31718523')) {function content_5208c61f5913e2_31718523($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("common/header.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!--
广告前端系统统一DEMO项目
当前版本：@MASTERVERSION@
构建时间：@BUILDDATE@
@COPYRIGHT@
-->
<section class="section">
    <div class="container">
        <h1>模型和模型表单设计</h1>
        <!-- 二级导航 -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="designer.php?type=form">表单设计</a></li>
            <li><a href="designer.php?type=model">模型设计</a></li>
            <li><a href="designer.php?type=list">属性列表</a></li>
        </ul>
        <aside class="left span3">
            <h3 class="title">模型</h3>
            <div id="model_list_container"></div>
            <h3 class="title">模型的属性</h3>
            <div id="attribute_list_container"></div>
        </aside>
        <section id="form_placeholder" class="span6">
        </section>
        <aside class="span3">
            <h3 class="title">模型的表单<span class="model-ctrl"><a id="form_create_handler" title="添加新的模型表单" href="#"><i class="icon-plus"></i></a></span></h3>
            <div id="form_list_container"></div>
            <h3 class="title">表单对象的属性<span class="model-ctrl"><a id="formattribute_create_handler" title="添加新的表单对象属性" href="#"><i class="icon-plus"></i></a></span></h3>
            <div id="formattribute_list_container"></div>
            <h3 class="title">表单对象的验证<span class="model-ctrl"><a id="validation_create_handler" title="添加新的表单对象验证" href="#"><i class="icon-plus"></i></a></span></h3>
            <div id="validation_list_container"></div>
        </aside>
    </div>
</section>
<script type="text/javascript">
require(['jquery', 'bootstrap'], function($, bootstrap){
    alert($.trim);
});
/*
requirejs();
*/
</script>
<script type="text/javascript">
/*
seajs.use([
'jquery', 
'/model-engine/js/enum', 
'/model-engine/js/formdesigner',
'/model-engine/js/modelform', 
'/model-engine/js/modelformengine', 
'/model-engine/js/modellist', 
'/model-engine/js/modelctrllist', 
'/model-engine/js/modeleditablelist', 
'/model-engine/js/linkagelist'
], function($, enu, fd, mf, mfe, ml, mcl, mel, ll){
    $(function() {
        var FormDesigner = fd.FormDesigner,
            ModelType = enu.ModelType,
            ModelForm = mf.ModelForm,
            ModelFormEngine = mfe.ModelFormEngine,
            ModelList = ml.ModelList,
            ModelCtrlList = mcl.ModelCtrlList,
            ModelEditableList = mel.ModelEditableList,
            LinkageList = ll.LinkageList;

        var model_list = new ModelList("#model_list_container", ModelType.MODEL, {
                'value': 'model_code', 
                'text': 'model_name'
            }),
            attribute_list = new ModelList("#attribute_list_container", ModelType.ATTRIBUTE, { 
                'value': 'attribute_id', 
                'text': 'attribute_name',
                'extparams': { 'editable': 1 } 
            } ),
            form_list = new ModelCtrlList("#form_list_container", ModelType.MODELFORM, { 
                'value': 'form_id', 'text': 'form_name', 
                'name': 'form_name', 

                'add': {
                    'handler': '#form_create_handler', 
                    'form_name': 'frm_form_create'
                },

                'edit': {
                    'key': 'form_id', 
                    'form_name': 'frm_form_update'
                },

                'clone': {
                    'service': '/model-engine/svr/form_clone.php',
                    'options' : { 'model': ModelType.MODELFORM, 'id': '{ form_id }' }             
                },

                'delete': {
                    'service': '/model-engine/svr/form_delete.php',
                    'options' : { 'model': ModelType.MODELFORM, 'id': '{ form_id }' }             
                }
            } ),
            formattribute_list = new ModelEditableList("#formattribute_list_container", 'formattribute', { 
                'init_model': ModelType.MODELFORM,
                'add_handler': '#formattribute_create_handler',

                'edit': {
                    'service': '/model-engine/svr/model_attribute_edit.php',
                    'options' : { 'model': '{ model_code }', 'id': '{ model_id }', 'name': '{ name }', 'value': '{ value }' }             
                },

                'delete': {
                    'service': '/model-engine/svr/model_attribute_delete.php',
                    'options' : { 'model': '{ model_code }', 'id': '{ model_id }', 'attr': '{ attribute_id }' }             
                }
            } ),
            validation_list = new ModelCtrlList("#validation_list_container", ModelType.VALIDATION, { 
                'value': 'validation_id', 
                'text': '[{ validation_method }]{ validation_message }',
                'name': 'validation_method', 

                'add': {
                    'handler': '#validation_create_handler', 
                    'form_name': 'frm_validation_create',
                    'options': { 'form_id': 'code', 'model_code': 'type' }
                },

                'edit': {
                    'key': 'validation_id', 
                    'form_name': 'frm_validation_update'
                },

                'delete': {
                    'service': '/model-engine/svr/model_delete.php',
                    'options' : { 'model': ModelType.VALIDATION, 'id': '{ validation_id }' }             
                }
            } );
        
        function create_designer() {
            var engine = ModelFormEngine.getInstance(),
                item = form_list.selectedItem;
            engine.clean();
            if(item.hasOwnProperty('form_id')){
                var form = engine.create(item['form_id'], item['form_name'], {
                        'init_key': 'id',
                        'placeholder': '#form_placeholder', 
                        'fixedfields': { 'model_code': ModelType.MODEL, 'form_mode_code': ModelType.MODELFORM }
                    } ),
                    designer = new FormDesigner(form, formattribute_list, validation_list);
                form.addEventListener(ModelForm.Event.LOADED, $.proxy(form.show, form));
                form.addEventListener(ModelForm.Event.LOADED, $.proxy(designer.initialise, designer));
            }
        }    

        form_list.addEventListener(ModelList.Event.CHANGE, create_designer);
        formattribute_list.addEventListener(ModelEditableList.Event.EDITED, create_designer);
        validation_list.addEventListener(ModelCtrlList.Event.EDITED, create_designer);
        validation_list.addEventListener(ModelCtrlList.Event.CLOSED, create_designer);
             
        var ma_linkage = new LinkageList(model_list, attribute_list),
            mf_linkage = new LinkageList(model_list, form_list),
            fa_linkage = new LinkageList(form_list, formattribute_list),
            fv_linkage = new LinkageList(form_list, validation_list);
        ma_linkage.load();
    } );
} );
*/
</script>
<?php echo $_smarty_tpl->getSubTemplate ("common/footer.tpl.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>