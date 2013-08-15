define([
    'jquery',
    'model-engine/js/util',
    'model-engine/js/modellist',
    'model-engine/js/modelform',
    'model-engine/js/modelformengine'
], function($, util, ml, mf, mfe) {
    var format = util.format,
        ModelList = ml.ModelList,
        ModelForm = mf.ModelForm,
        ModelFormEngine = mfe.ModelFormEngine;
    
    var FormType = {'ADD': 'add', 'EDIT': 'edit'},
        OperateType = {'CLONE': 'clone', 'DELETE': 'delete'};

    /**
     * 模型可管理列表（可添加、编辑和删除）。
     * @param container {String} 模型列表的容器。
     * @param type {String} 模型的编码。
     * @param options {Object} 模型列表的可选项。
     */
    function ModelCtrlList(container, type, options){
        ModelList.call(this, container, type, options);
        this.callbackDict = $.extend(this.callbackDict, {'edited': [], 'closed': []});
        this.options = $.extend(this.options, { 
            /**
            * 数据项对外显示名称的字段。
            */
            'name': 'name',

            /**
            * 数据添加的配置信息。
            */
            'add': {
                /**
                * 触发打开添加表单的对象。
                */
                'handler': '', 

                /**
                * 如果是字符串，是添加表单的名称。
                * 如果是函数，则该函数返回添加表单的名称。
                */
                'form_name': '',

                /**
                * 表单提交时，固定会提交的键值对数据。
                * 键是表单提交的参数。
                * 值作为属性名，把当前对象的指定属性值作为表单提交的值。
                */
                'options': {},

                /**
                * 提供添加行为的后端服务地址。
                */
                'service': ''
            },

            /**
            * 数据编辑的配置信息。
            */
            'edit': {
                /**
                * 数据项的主键。
                */
                'key': '',

                /**
                * 如果是字符串，是编辑表单的名称。
                * 如果是函数，则该函数返回编辑表单的名称。
                */
                'form_name': '',

                /**
                * 提供编辑行为的后端服务地址。
                */
                'service': ''
            },

            /**
            * 列表项克隆的配置信息。
            */
            'clone': {
                'service': '',
                /**
                * 参数为键值对的对象。
                * 键作为参数名提交给服务器端，
                * 以值作为列表项的键名提交给服务器端
                */
                'options' : {}
            },


            /**
            * 列表项删除的配置信息。
            */
            'delete': {
                'service': '',
                /**
                * 参数为键值对的对象。
                * 键作为参数名提交给服务器端，
                * 以值作为列表项的键名提交给服务器端
                */
                'options' : {}             
            }
        }, options);
        this.editable = this.options[FormType.EDIT]['key'] && this.options[FormType.EDIT]['form_name'];
        this.allow_cloning = !!this.options[OperateType.CLONE]['service'];
        this.allow_delete = !!this.options[OperateType.DELETE]['service'];
        var handler = this.options[FormType.ADD]['handler'];
        if(handler){
            $(handler).click($.proxy(this.openAddForm, this));
        }
    };
    $.extend(ModelCtrlList.prototype, ModelList.prototype, {
        /**
         * 打开添加数据的表单。
         */
        openAddForm: function(){
            this.openForm(FormType.ADD);
        },
        openEditForm: function(def){
            this.openForm(FormType.EDIT, def);
        },
        openForm: function(type, def){
            var id, name, service, options = {'fixedfields': {}};
            switch(type){
                case FormType.ADD:
                    id = FormType.ADD + '_' + this.code,
                    name = this.options[FormType.ADD]['form_name'],
                    service = this.options[FormType.ADD]['service'];
                    var fixedfields = this.options[FormType.ADD]['options'];
                    if(fixedfields){
                        for(var key in fixedfields){
                            var prop = fixedfields[key];
                            options['fixedfields'][key] = this[prop];
                        }
                        def = options['fixedfields'];
                    }   
                    break;
                case FormType.EDIT:
                    var key = this.options[FormType.EDIT]['key'],
                        service = this.options[FormType.EDIT]['service'];
                    if(def.hasOwnProperty(key)){
                        id = FormType.EDIT + '_' + def[key];
                        options['fixedfields'][key] = def[key];
                    }else{
                        alert('没有主键，无法编辑！');
                    }
                    name = this.options[FormType.EDIT]['form_name'];
                    options['def_name'] = this.options['name'];
                    break;
            }
            if (service) {
                options['action_service'] = service;
            };
            if('function' == typeof(name)){
                name = name();
            }
            if(name){
                var engine = ModelFormEngine.getInstance();
                engine.clean();
                if (id) {
                    id = name + '_' + id;
                }else{
                    id = name;
                }
                var form = engine.create(id, name, options, def);
                if(form.loaded){
                    ($.proxy(form.show, form))();
                }else{
                    form.addEventListener(ModelForm.Event.LOADED, $.proxy(form.show, form));
                    form.addEventListener(ModelForm.Event.SUCCESS, $.proxy(this.reload, this));
                    form.addEventListener(ModelForm.Event.CLOSED, $.proxy(this.close, this));
                }
            }
        },

        close: function(){
            this.dispatchEvent(ModelCtrlList.Event.CLOSED);
        },

        reload: function(){
            this.load();
            this.dispatchEvent(ModelCtrlList.Event.EDITED);
        },
        
        /**
         * 向列表项中追加内容。
         * @param li {jQuery} 列表项对象。
         */
        appendToListItem: function(li, idx){
            var ctrl, edit, remove;
            ctrl = $('<span class="model-ctrl" />');
            li.append(ctrl);
            if (this.allow_cloning) {
                this.appendCloneHandler(li, idx, ctrl);
            }
            if (this.editable) {
                this.appendEditHandler(li, idx, ctrl);
            };
            if (this.allow_delete) {
                this.appendRemoveHandler(li, idx, ctrl);
            };
        },

        appendCloneHandler: function(li, idx, ctrl){
            clone = $('<a title="" href="#"><i class="icon-share"></i></a>');
            clone.prop('item', this.items[idx]);
            clone.prop('o', this);
            clone.click(function(){
                var item = $(this).prop('item'),
                    o = $(this).prop('o');
                ($.proxy(o.operate, o))(OperateType.CLONE, item);
            });
            ctrl.append(clone);
        },

        appendEditHandler: function(li, idx, ctrl){
            edit = $('<a title="" href="#"><i class="icon-edit"></i></a>');
            edit.prop('item', this.items[idx]);
            edit.prop('o', this);
            edit.click(function(){
                var item = $(this).prop('item'),
                    o = $(this).prop('o');
                o.openEditForm(item);
            });
            ctrl.append(edit);
        },

        appendRemoveHandler: function(li, idx, ctrl){
            remove = $('<a title="" href="#"><i class="icon-remove"></i></a>');
            remove.prop('idx', idx);
            remove.prop('o', this);
            remove.prop('container', $(li));
            remove.click(function(){
                var check = confirm('确定要删除这条内容吗？');
                if(check){
                    var idx = $(this).prop('idx'),
                        o = $(this).prop('o'),
                        container = $(this).prop('container');
                    var item = o.items[idx];
                    if(item['leaves']){
                        check = confirm('这条内容拥有子对象。\n如果删除这条内容，所有子对象也都将被删除。\n依然确定要删除这条内容吗？');
                    }
                    if(check){
                        ($.proxy(o.operate, o))(OperateType.DELETE, item);
                        $(container).remove();
                    }
                }
            });
            ctrl.append(remove);
        },
        
        /**
         * 对列表项进行操作。
         * @param type {OperateType} 操作的类型。
         * @param item {Object} 列表项。
         */
        operate: function(type, item){
            var options = {},
                settings = this.options[type];
            for(var key in settings['options']){
                var str = settings['options'][key];
                if(str.indexOf('{') < 0){
                    options[key] = str;
                }else{
                    options[key] = format(str, item);
                }

            }
            $.post(settings['service'], options, $.proxy(this.operateSuccess, this));
        },

        /**
         * 数据库操作后的回调函数。
         * @param data {Int} 是否成功。
         */
        operateSuccess: function(data){
            data *= 1;
            if(data){
                this.reload();
            }else{
                alert('操作失败了！');
            }
        }
    });
    
    /**
     * 模型可管理列表的事件。
     */
    ModelCtrlList.Event = $.extend(ModelList.Event, {'EDITED': 'edited', 'CLOSED': 'closed'});
    
    return {
        'ModelCtrlList': ModelCtrlList
    };
});
 