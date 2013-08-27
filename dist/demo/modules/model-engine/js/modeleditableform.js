define([
    'jquery',
    'model-engine/js/enum',
    'model-engine/js/event'
], function($, enu, eve) {
    var ModelType = enu.ModelType,
        EventDispatcher = eve.EventDispatcher;
            
    /**
     * 为模型可编辑列表服务的添加表单对象。
     */
    function ModelEditableForm(master, options){
        EventDispatcher.call(this);
        this.master = master;
        this.items = this.master.items;
        this.options = $.extend({
            'service': '', 
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
    };
    $.extend(ModelEditableForm.prototype, EventDispatcher.prototype, {
        /**
         * 打开添加数据的表单。
         */
        open: function(){
            if (this.options['service']) {
                var options = { 
                    't': ModelType.ATTRIBUTE, 
                    'code': this.master.model, 
                    'editable': 1 
                };
                $.getJSON(this.options['service'], options, $.proxy(this.checkEditableAttributes, this));
            }else{
                alert('参数不全！');
            }
        },

        /*
        * [{"attribute_id":"11","attribute_name":"model_code",
        * "attribute_comment":"\u6a21\u578b\u7684\u7f16\u7801","value_type":"str",
        * "default_value":"empty","model_code":"modelform","category_id":"0",
        * "list_id":"0","is_ext":"0","is_editable":"1","is_autoupdate":"0",
        * "is_primary":"0","position_order":"0","update_time":"1364974632",
        * "create_time":"1364974632"}, ... ...]
        */
        checkEditableAttributes: function(items){
            var i, dict = {};
            for(i = 0; i < items.length; i++){
                //TODO：写死字段名，不理想
                if((1 * items[i]['is_ext']) && (1 * items[i]['is_editable'])){
                    dict[items[i]['attribute_name']] = items[i];
                }
            }
            for(i = 0; i < this.items.length; i++){
                //TODO：写死字段名，不理想
                var name = this.items[i]['name'];
                if(dict.hasOwnProperty(name)){
                    delete dict[name];
                }
            }
            items = [];
            for(var key in dict){
                items.push(dict[key]);
            }
            if(items.length > 0){
                this.createAddForm(items);
            }else{
                alert('没有可以添加的属性，去编辑现有的属性吧！');
            }
        },
                
        /**
         * 创建数据表单。
         */
        createAddForm: function(items){
            var idx = this.master.list.length;
            if(idx == this.master.items.length && this.master.ul){
                var li, sep,
                div_text, text, text_show, spn_text, ctrl_show_text, remove,
                ctrl_show_editable, ctrl_edit_editable, editable,
                div_editable, editable_show, editable_edit, spn_editable, txt_editable;
                li = $('<li />');
                $(this.master.ul).append(li);
                this.master.list[idx] = li;
                var key_value  = this.master.options['value'],
                    key_text  = this.master.options['text'],
                    key_editable  = this.master.options['editable'];
                this.master.items[idx] = {
                    key_value: '', 
                    key_text: '', 
                    key_editable: ''
                };
                div_text = $('<div />');
                li.append(div_text);
                text_edit = $('<div />');
                div_text.append(text_edit);
                ddl_text = $('<select />');
                for(var i = 0; i < items.length; i++){
                    ddl_text.append($('<option value=\'' + items[i]['attribute_name'] + '\'>' + items[i]['attribute_comment'] + '</option>'));
                }
                text_edit.append(ddl_text);
            
                div_editable = $('<div />');
                li.append(div_editable);
                editable = this.master.items[idx][this.master.options['editable']];
                editable_edit = $('<div />');
                div_editable.append(editable_edit);
                txt_editable = $('<input type="text" />');
                editable_edit.append(txt_editable);
                txt_editable.val(editable);
                ctrl_edit_editable = $('<span class="model-ctrl" />');
                editable_edit.append(ctrl_edit_editable);
                ok = $('<a title="" href="#"><i class="icon-ok"></i></a>');
                ctrl_edit_editable.append(ok);
                ok.prop('o', this.master);
                ok.prop('ddl', ddl_text);
                ok.prop('val', txt_editable);
                ok.click(function(){
                    var o = $(this).prop('o'),
                        name = $($(this).prop('ddl')).val(),
                        val = $($(this).prop('val')).val();
                    var options = {
                        'model': o.model, 
                        'id': o.code, 
                        'name': name, 
                        'value': val
                    };
                    $.post(o.options['edit']['service'], options, $.proxy(o.operateSuccess, o));
                });
            }
        }
    });

    return {
        'ModelEditableForm': ModelEditableForm
    };
});

