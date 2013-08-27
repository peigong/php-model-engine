define([
    'jquery', 
    'model-engine/js/enum', 
    'model-engine/js/modelform', 
    'model-engine/js/modelformengine'
], function($, enu, mf, mfe) {
    var ModelType = enu.ModelType,
        ModelCategory = enu.ModelCategory,
        ModelForm = mf.ModelForm,
        ModelFormEngine = mfe.ModelFormEngine;
        
    /**
     * 表单设计器。
     * @param form {ModelForm} 需要进行设计的表单对象。
     * @param attribute {ModelEditableList} 用于编辑属性值的模型列表对象。
     * @param validation {ModelCtrlList} 用于配置表单验证方法的模型列表对象。
     */
    function FormDesigner(form, attribute, validation){
        this.form = form;
        this.attribute = attribute;
        this.validation = validation;
        this.input_list = null;
        this.containerTypes = ['modelform', 'tabcontainer', 'rowcontainer'];
        this.input_create_forms = {
            'tabcontainer': 'frm_form_tabcontainer_create',
            'rowcontainer': 'frm_form_rowcontainer_create',
            'textinput': 'frm_form_text_create',
            'selectinput': 'frm_form_select_create',
            'checkboxinput': 'frm_form_checkbox_create',
            'checkboxlistinput': 'frm_form_checkboxlist_create',
            'radiolistinput': 'frm_form_radiolist_create',
            'fileinput': 'frm_form_file_create'
        };
        this.delete_service = './svr/model_delete.php';
        this.list_service = './svr/model_list_fetch.php';
    };
    FormDesigner.prototype = {
        /**
         * 初始化。
         */
        initialise: function(){
            if(this.form.body){
                $(this.form.body).css({
                    'padding-right': '0px'
                });
            }
            this.castrate();
            this.addCtrl();

            var idx = -1,
                container = this.form.containers[0],
                settings = $(container).prop('settings');;
            this.setSlaveList(settings);
            this.setContainerBorder(idx);
        },
        
        castrate: function(){
            //取消表单的功能
            var options = {
                submit :function(){},
                submitSuccess :function(){},
                reset :function(){},
                close :function(){}
            };
            for(var key in options){
                this.form[key] = options[key];
            }
        },
        
        addCtrl: function(){
            var counts = this.form.containers.length;
            if(counts > 0){
                for(var i = 0; i < counts; i++){
                    var container = this.form.containers[i], 
                        ctrl = $('<span />');
                    $(container).prepend(ctrl);
                    this.appendPlusCtrl(i, ctrl, container);
                    this.appendEditCtrl(i, ctrl, container);
                    this.appendRemoveCtrl(i, ctrl, container);
                }
            }else{
                alert('没有任何表单对象！');
            }
        },
        
        appendPlusCtrl: function(idx, ctrl, container){
            var settings = $(container).prop('settings');
            if($.inArray(settings.code, this.containerTypes) > -1){
                var ddl_input_list = $('<select />');
                ctrl.append(ddl_input_list);
                this.input_list = ddl_input_list;
                var options = { 't': ModelType.MODEL, 'code': ModelCategory.FORMINPUT };
                $.getJSON(this.list_service, options, $.proxy(function(items){
                        this.fillInputList(ddl_input_list, items)
                    }, this));
                var plus = $('<a title="" href="#"><i class="icon-plus"></i></a>');
                plus.prop('designer', this);
                plus.prop('ddl', ddl_input_list);
                plus.prop('container', $(container));
                plus.click(function(){
                    var container = $(this).prop('container'),
                        ddl = $(this).prop('ddl'),
                        code = $.trim(ddl.val());
                    if(code.length > 0){
                        var engine = ModelFormEngine.getInstance(),
                            settings = $(container).prop('settings'),
                            designer = $(this).prop('designer'),
                            forms = designer.input_create_forms;
                        if(forms.hasOwnProperty(code) && forms[code]){
                            var frm_id = forms[code];
                            var form = engine.create(frm_id, frm_id, {
                                    /*宿主模型的用途主要是在特定情况下获取模型字段的列表*/
                                    'parasitifer': designer.form.model, //表单宿主的模型
                                    'fixedfields': {
                                            'model_code': designer.form.model, 
                                            'form_mode_code': code,
                                            'parent_id': settings['id']
                                        }
                                    });
                            form.addEventListener(ModelForm.Event.LOADED, $.proxy(form.show, form));
                            form.addEventListener(ModelForm.Event.SUCCESS, $.proxy(designer.form.reload, designer.form));
                            if(form.loaded){
                                ($.proxy(form.show, form))();
                            }
                        }
                    }
                });
                ctrl.append(plus);
            }
        },
        
        appendEditCtrl: function(idx, ctrl, container){
            var edit = $('<a title="" href="#"><i class="icon-edit"></i></a>');
            edit.prop('idx', idx);
            edit.prop('designer', this);
            edit.click(function(){
                var idx = $(this).prop('idx'), 
                    designer = $(this).prop('designer'), 
                    container = designer.form.containers[idx],
                    settings = $(container).prop('settings');
                designer.setSlaveList(settings);
                designer.setContainerBorder(idx);
            });
            ctrl.append(edit);
        },
        
        appendRemoveCtrl: function(idx, ctrl, container){
            var settings = $(container).prop('settings');
            if(settings.code != ModelType.MODELFORM){
                var remove = $('<a title="" href="#"><i class="icon-remove"></i></a>');
                remove.prop('idx', idx);
                remove.prop('o', this);
                remove.prop('container', $(container));
                remove.click(function(){
                    var check = confirm('确定要删除这条内容吗？');
                    if(check){
                        var idx = $(this).prop('idx'),
                            o = $(this).prop('o'),
                            container = $(this).prop('container'),
                            settings = $(container).prop('settings');
                        $(container).remove();
                        /*
                        {
                            "id":"","model":"","code":"","name":"","description":"",
                            "attributes":{},
                            "items":[
                                {"id":"","model":"","code":"","name":"","description":"""attributes":{},"items":[]}
                            ]
                        }
                        */
                        
                        var options = {
                            't': settings.code,
                            'model': settings.code,
                            'id': settings.id
                        };
                        $.post(o.delete_service, options, $.proxy(o.operateSuccess, o));
                    }
                });
                ctrl.append(remove);
            }
        },
        
        fillInputList: function(ddl, items){
            if(ddl && items){
                ddl.empty();
                for(var i = 0; i < items.length; i++){
                    ddl.append($('<option value=\'' + items[i]['model_code'] + '\'>' + items[i]['model_name'] + '</option>'));
                }
            }
        },

        setSlaveList: function(settings){
            var id = settings['id']
                code = settings['code'];
            this.attribute.code = this.validation.code = id;
            this.attribute.model = this.validation.model = code;
            this.attribute.load(id);
            this.validation.load(id);
        },

        setContainerBorder: function(idx){
            var counts = this.form.containers.length;
            if(counts > 0){
                $(this.form.containers[0]).show();
                for(var i = 0; i < counts; i++){
                    var container = this.form.containers[i],
                        oCss = {
                            'border-color': '#cccccc',
                            'border-style': 'dashed',
                            'border-width': '1px',
                            'margin-bottom': '0px'
                        };
                    if(i == idx){
                        oCss = {
                            'border-color': '#cccccc',
                            'border-style': 'double',
                            'border-width': '3px'
                        }
                    }
                    $(container).css(oCss);
                }
            }
        },
        
        /**
         * 数据库操作后的回调函数。
         * @param data {Int} 是否成功。
         */
        operateSuccess: function(data){
            data = data * 1;
            if(data){
            }else{
                alert('操作失败了！');
            }
        }
    };
    
    return {
        'FormDesigner': FormDesigner
    };
});

