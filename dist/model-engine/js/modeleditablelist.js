/**
 * 广告前端系统统一DEMO项目
 * 模型及模型表单引擎。
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */
define(function(require, exports, module) {
    var $ = jQuery = require('jquery'),
        enu = require('/model-engine/js/enum'),
        ml = require('/model-engine/js/modellist'),
        mef = require('/model-engine/js/modeleditableform'),
        
        ModelType = enu.ModelType,
        ModelList = ml.ModelList,
        ModelEditableForm = mef.ModelEditableForm;

    var OperateType = {'EDIT': 'edit', 'DELETE': 'delete'};
        
    /**
     * 模型可编辑列表（可在自身添加、编辑和删除）。
     * @param container {String} 模型列表的容器。
     * @param type {String} 模型的编码。
     * @param options {Object} 模型列表的可选项。
     */
    function ModelEditableList(container, type, options){
        ModelList.call(this, container, type, options);
        this.callbackDict = $.extend(this.callbackDict, {'edited': []});
        this.options = $.extend(this.options, {
            'add_handler': '', 

            /*可编辑修改的列表项字段名*/
            'editable': 'value',

            'edit': {
                'service': '',
                /**
                * 参数为键值对的对象。
                * 键作为参数名提交给服务器端，
                * 以值作为列表项的键名提交给服务器端
                */
                'options' : {}             
            },

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
        this.editable = !!this.options[OperateType.EDIT]['service'];
        this.allow_delete = !!this.options[OperateType.DELETE]['service'];
        var handler = this.options['add_handler'];
        if(handler){
            var form = new ModelEditableForm(this, {
                'model': this.model,
                'service': this.options['service'],
                'add': this.options[OperateType.EDIT]
            });
            $(handler).click($.proxy(form.open, form));
        }
    };
    $.extend(ModelEditableList.prototype, ModelList.prototype, {
        /**
         * 向列表项中追加内容。
         * @param li {jQuery} 列表项对象。
         */
        appendToListItem: function(li, idx){
            var item = this.items[idx];
            var sep, ctrl;
            sep = $('<span />');
            li.append(sep);
            sep.html('：');
            ctrl = $('<span class="model-ctrl" />');
            li.append(ctrl);
            if((item['ext'] * 1) && this.allow_delete){
                this.appendRemoveHandler(li, idx, ctrl);
            }
            if (this.editable) {
                this.appendEditHandler(li, idx, ctrl);
            };
            
        },

        appendRemoveHandler: function(li, idx, ctrl){
            var remove = $('<a title="" href="#"><i class="icon-remove"></i></a>');
            remove.prop('idx', idx);
            remove.prop('o', this);
            remove.prop('container', $(li));
            remove.click(function(){
                var check = confirm('确定要删除这条内容吗？');
                if(check){
                    var idx = $(this).prop('idx'),
                        o = $(this).prop('o'),
                        container = $(this).prop('container'),
                        item = o.items[idx];
                    ($.proxy(o.operate, o))(OperateType.DELETE, item);
                    $(container).remove();
                }
            });
            ctrl.append(remove);
        },
        
        appendEditHandler: function(li, idx, ctrl){
            var key_editable = this.options['editable'], 
                ctrl_show_editable, ctrl_edit_editable, editable,
                div_editable, editable_show, editable_edit, spn_editable, txt_editable;
            div_editable = $('<div />');
            li.append(div_editable);
            editable = this.items[idx][key_editable];
            
            editable_show = $('<div />');
            div_editable.append(editable_show);
            spn_editable = $('<span />');
            editable_show.append(spn_editable);
            spn_editable.html(editable);
            ctrl_show_editable = $('<span class="model-ctrl" />');
            editable_show.append(ctrl_show_editable);
            edit = $('<a title="" href="#"><i class="icon-edit"></i></a>');
            ctrl_show_editable.append(edit);
            
            editable_edit = $('<div style="display:none;" />');
            div_editable.append(editable_edit);
            txt_editable = $('<input type="text" />');
            editable_edit.append(txt_editable);
            txt_editable.val(editable);
            ctrl_edit_editable = $('<span class="model-ctrl" />');
            editable_edit.append(ctrl_edit_editable);
            ok = $('<a title="" href="#"><i class="icon-ok"></i></a>');
            ctrl_edit_editable.append(ok);
            
            edit.prop('show', editable_show);
            edit.prop('edit', editable_edit);
            edit.click(function(){
                var show = $(this).prop('show'),
                    edit = $(this).prop('edit');
                $(show).hide();
                $(edit).show();
            });
            ok.prop('idx', idx);
            ok.prop('o', this);
            ok.prop('spn', spn_editable);
            ok.prop('txt', txt_editable);
            ok.prop('show', editable_show);
            ok.prop('edit', editable_edit);
            ok.click(function(){
                var idx = $(this).prop('idx'),
                    o = $(this).prop('o'),
                    spn = $(this).prop('spn'),
                    txt = $(this).prop('txt'),
                    show = $(this).prop('show'),
                    edit = $(this).prop('edit'),
                    val = $(txt).val();
                $(spn).html(val);
                $(show).show();
                $(edit).hide();

                var item = o.items[idx],
                    key_editable = o.options['editable'];
                item[key_editable] = val;
                ($.proxy(o.operate, o))(OperateType.EDIT, item);
            });
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
                this.dispatchEvent(ModelEditableList.Event.EDITED);
                this.load();
            }else{
                alert('操作失败了！');
            }
        }
    });
    
    /**
     * 模型可编辑列表的事件。
     */
    ModelEditableList.Event = $.extend(ModelList.Event, {'EDITED': 'edited'});
    
    exports.ModelEditableList = ModelEditableList;
});

