define(['jquery'], function(require, exports, module) {
    require('jquery.ui');
    require('jquery-validate/jquery.validate.js');
    require('jquery-validate/localization/messages_zh.js');                            
    
    var enu = require('model-engine/js/enum'),
        e = require('model-engine/js/event'),
        util = require('model-engine/js/util');


    require('model-engine/css/model-engine.css');
    require('model-engine/js/plugs/checkboxinput.plug');
    require('model-engine/js/plugs/fileinput.plug');
    require('model-engine/js/plugs/radiolistinput.plug');
    require('model-engine/js/plugs/rowcontainer.plug');
    require('model-engine/js/plugs/selectinput.plug');
    require('model-engine/js/plugs/textinput.plug');
    

    var ModelType = enu.ModelType,
        FormfieldPrefix = enu.FormfieldPrefix,
        Event = e.Event,
        EventDispatcher = e.EventDispatcher,
        format = util.format;
   
    /**
     * 模型表单类。
     * @param id {String} 模型表单的ID。
     * @param name {String} 模型表单的名称。
     * @param options {Object} 表象的可选项。
     * @param def {Object} 表单的默认值对象。
     */
    function ModelForm(id, name, options, def){
        EventDispatcher.call(this);
        this.id = id || '';       
        this.name = name || '';       
        this.def = def || {};       
        this.model = null;
        this.popup = false;
        this.loaded = false;
        this.settings = null; 
        this.controls = {};
        this.containers = [];
        this.backdrop = null;
        this.container = null;
        this.body = null;
        this.callbackDict = $.extend(this.callbackDict, {'success': [], 'loaded': [], 'closed': []}); 
        this.options = $.extend({
            /**
            * 表单数据对象中用作默认值键的属性。
            * 取值范围：name、field
            */
            'def_key': 'field',

            /**
            * 默认值中，表示名称的字段。
            * 用于替换表单title中的{name}占位符。
            */
            'def_name': 'name',
            /**
             * 拉取表单配置数据的服务器端服务
             **/
            'dynamic_fetch_service': './svr/model_form_fetch.php',
            'static_fetch_service': '',
            /*接收表单提交数据的服务器端服务*/
            'action_service': './svr/model_form_save.php',
            /*为上传提供后端服务的地址*/
            'upload_service': './svr/file_upload.php',
            'placeholder': '', 
            'fixedfields': {}
        }, options);
        this.placeholder = this.options['placeholder'];
        var oThis = this;
        this.validate = {
            debug: true,
            rules: {},
            messages: {},
            errorPlacement: function(error, element){
                var pos = $(element).position(),
                    height = $(element).height() + $(error).height(),
                    top = pos.top - height - 15, left = pos.left;
                top = (top < 0) ? 0 : top;
                top += 'px';
                left += 'px';
                $(error).css({
                    top: top,
                    left: left
                });
                $(element).after($(error));
            },
            submitHandler: function(){
                oThis.submit();
            }
        };
        this.containerId = 'modelengine_form_container_' + this.id;
    };
    $.extend(ModelForm.prototype, EventDispatcher.prototype, {
        /**
         * 初始化。
         */
        initialise: function(settings){
            this.settings = settings;
            if(this.settings.hasOwnProperty('id')){
                this.model = settings.model;
                this.create();
                this.dispatchEvent(ModelForm.Event.LOADED);
                this.loaded = true;
            }else{
                this.remove();
            }
        },
        
        /**
         * 从服务器加载表单对象的配置。
         */
        load: function(data){
            $.getJSON(this.options['dynamic_fetch_service'], data, $.proxy(this.initialise, this));
        },
        
        /**
         * 重新加载表单对象。
         */
        reload: function(){
            if(this.settings.hasOwnProperty('id')){
                var data = { 'id': this.settings['id'] };
                this.remove();
                this.load(data);
            }
        },
        
        /**
         * 创建模型表单。
         */
        create: function(){
            var container, container, form,
                settings = this.settings,
                items = settings.items,
                attributes = settings.attributes;
            
            container = this.createContainer();
            this.container = container;
            this.containers.push(container);
            if($(container).draggable){
                $(container).draggable({ handle: ".draggable_handle" });
            }
            form = this.createForm(container);
            this.createHeader(form);
            if(items){
                this.body = $('<div class="body" />');
                if(attributes.bodywidth || attributes.bodyheight){
                    this.body.css('overflow', 'auto');
                    if(attributes.bodywidth){this.body.css('width', attributes.bodywidth + 'px');}
                    if(attributes.bodyheight){this.body.css('height', attributes.bodyheight + 'px');}
                }
                form.append(this.body);
                for(var i = 0; i < items.length; i++){
                    this.createItem(this.body, items[i]);
                }
            }
            this.createFooter(form);

            var rules = this.validate.rules;
            if(rules){
                for(var key in rules){
                    var methods = rules[key];
                    if (methods) {
                        for(var method in methods){
                            if (('string' == typeof(methods[method])) && (0 == methods[method].indexOf('#'))) {
                                //如果第一个字符是#，表单参数指向一个表单对象，需要在表单加载完毕后处理一下。
                                var param = methods[method].substr(1);
                                for(var id in this.controls){
                                    if(this.controls[id]['name'] == param){
                                        this.validate.rules[key][method] = '#' + id;
                                    }
                                }
                            };
                        };
                    }
                }
                
            }
            form.validate(this.validate);
        },
        
        /**
         * 创建表单容器。
         */
        createContainer: function(){
            var placeholder, backdrop, container, form,
                settings = this.settings;
            this.popup = true;
            if(this.placeholder){
                placeholder = $(this.placeholder);
                this.popup = false;
            }else{
                placeholder = $('body');
            }
            container = $('#' + this.containerId)
            if(container.length > 0){
                container.remove();
            }
            
            container = $('<div />');
            container.attr('id', this.containerId);
            container.attr('class', 'model-form');
            if(this.popup){
                backdrop = $('<div />');
                backdrop.attr('style', 'background-color:#000000;opacity:0.8;position:fixed;top:0px;left:0px;right:0px;bottom:0px;display:none;');
                placeholder.append(backdrop);
                this.backdrop = backdrop;
                container.attr('style', 'position:fixed;z-index:10000;display:none;');
            }else{
                container.attr('style', 'display:none;');
            }
            container.prop('settings', settings);
            placeholder.prepend(container);
            return container;
        },
        
        /**
         * 创建表单对象。
         * @param container {Element} 表单容器。
         */
        createForm: function(container){
            var form, settings = this.settings;
            form = $('<form />');
            form.attr('id', settings.name);
            form.attr('name', settings.name);
            form.attr('class', 'form-horizontal');
            container.append(form);
            return form;
        },      
        
        /**
         * 创建表单头部。
         * @param form {Element} 表单对象。
         */
        createHeader: function(form){
            var form, header,
                settings = this.settings,
                attributes = settings.attributes;
            header = $('<header class="draggable_handle" />');
            form.append(header);
            if(attributes.title){
                var h3 = $('<h3 />'), 
                    name = this.options['def_name'],
                    title = attributes.title;
                if (this.def &&  name && this.def.hasOwnProperty(name) && this.def[name]) {
                    title = format(title, {'name': this.def[name]});
                };
                h3.html(title);
                header.append(h3);
            }
            if (this.popup) {
                var icon_close = $('<button type="button" class="close">&times;</button>');
                icon_close.click($.proxy(this.close, this));
                header.append(icon_close);
            };
        },
        
        /**
         * 创建表单底部。
         * @param form {Element} 表单对象。
         */
        createFooter: function(form){
            var id, footer,
                settings = this.settings,
                attributes = settings.attributes;
                
            footer = $('<footer class="form-actions draggable_handle" />');
            form.append(footer);

            if (this.popup) {
                var btn_close = $('<button />');
                id = 'btn_' + this.id + '_close';
                btn_close.attr('id', id);
                btn_close.attr('name', id);
                btn_close.attr('type', 'button');
                btn_close.attr('class', 'btn');
                btn_close.html('关闭');
                btn_close.click($.proxy(this.close, this));
                footer.append(btn_close);
            };

            var btn_reset = $('<button />');
            id = 'btn_' + this.id + '_reset';
            btn_reset.attr('id', id);
            btn_reset.attr('name', id);
            btn_reset.attr('type', 'button');
            btn_reset.attr('class', 'btn');
            btn_reset.html('复原');
            btn_reset.click($.proxy(this.reset, this));
            footer.append(btn_reset);
            
            var button_text = '确定';
            if(attributes.buttontext){
                button_text = attributes.buttontext;
            }
            var btn_submit = $('<button />');
            id = 'btn_' + this.id + '_create';
            btn_submit.attr('id', id);
            btn_submit.attr('name', id);
            btn_submit.attr('type', 'submit');
            btn_submit.attr('class', 'btn btn-primary');
            btn_submit.html(button_text);
            footer.append(btn_submit);
        },
        
        /**
         * 创建表单项。
         * @param {Element} container 表单项的容器。
         * @param {Object} settings 表单项的配置数据。
         * @param {Boolean} inline 表单项是否是行内元素。
         */
        createItem: function(container, settings, inline){
            var util, create, 
                code = settings.code, 
                validation = settings.validation,
                module = 'model-engine/js/plugs/' + code + '.plug',
                name = this.getControlName(settings),
                util = require(module);
            if(util){
                create = util.create;
                this.addValidation(name, validation);
            }else{
                create = function(){};
            }
            var def, key, attributes = settings.attributes;
            switch(this.options['def_key']){
                case 'name':
                    key = settings['name'];
                    break;
                case 'field':
                    key = attributes['field'];
                    break;
            }
            if (key && this.def && this.def.hasOwnProperty(key)) {
                def = this.def[key];
            };
            var ext = { 
                'inline': inline, 
                'upload_service': this.options['upload_service'],
                'createItem': $.proxy(this.createItem, this) 
            };
            create(this, container, settings, ext, def);
        },

        /**
        * 以统一的逻辑获取表单对象的ID和Name。
        * @param {Object} settings 表单项的配置数据。
        * @return {String} 表单对象的ID和Name。
        */
        getControlName: function(settings){
            return this.id + '_' + settings.name;
        },
        
        /**
         * 添加表单验证。
         * @param {String} name 表单对象的名称。
         * @param {Object} validation 表单验证的配置数据。
         */
        addValidation: function(name, validation){
            for(var i = 0; i < validation.length; i++){
                var valid = validation[i],
                    method = valid['validation_method'],
                    message = valid['validation_message'],
                    param = valid['validation_param'];
                if(!this.validate.rules[name]){
                    this.validate.rules[name] = {};
                }
                if (param) {
                    this.validate.rules[name][method] = param;
                }else{
                    this.validate.rules[name][method] = true;
                };
                if(message){
                    if(!this.validate.messages[name]){
                        this.validate.messages[name] = {};
                    }
                    this.validate.messages[name][method] = message;
                }
            }
        },

        /**
         * 提交表单数据。
         */
        submit: function(){
            var control, options = { 't': this.model }, fields = this.options['fixedfields'], val;
            if(fields){
                for(var key in fields){
                    options[key] = fields[key];
                }
            }
            for(var id in this.controls){
                control = this.controls[id];
                switch(control['type']){
                case ModelType.CHECKBOXINPUT:
                    val = $('#' + id).prop('checked');
                    val = val ? 1: 0;
                    break;
                case ModelType.RADIOLISTINPUT:
                    val = $('input[name=' + id + ']:checked').val();
                    break;
                case ModelType.TEXTINPUT:
                case ModelType.SELECTINPUT:
                case ModelType.CHECKBOXLISTINPUT:
                case ModelType.FILEINPUT:
                default:
                    val = $('#' + id).val();
                }
                if (control['field']) {
                    options[control['field']] = val;
                }else if (control['name']) {
                    options[control['name']] = val;
                }
            }
            $.post(this.options['action_service'], options, $.proxy(this.submitSuccess, this));
            this.close();
        },
        
        /**
         * 数据提交成功后的回调方法。
         */
        submitSuccess: function(data){
            if(data * 1) {
                this.dispatchEvent(ModelForm.Event.SUCCESS);
            }
        },
        
        /**
         * 重置表单为初始状态。
         */
        reset: function(){
            for(var id in this.controls){
                control = this.controls[id];
                var val = '';
                if (this.def.hasOwnProperty(control['field'])) {
                    val = this.def[control['field']];
                };
                switch(control['type']){
                case ModelType.CHECKBOXINPUT:
                    $('#' + id).prop('checked', !!val);
                    break;
                case ModelType.CHECKBOXLISTINPUT:
                case ModelType.RADIOLISTINPUT:
                    $('input[name=' + id + ']').attr("checked", val);
                    break;
                case ModelType.TEXTINPUT:
                case ModelType.SELECTINPUT:
                case ModelType.FILEINPUT:
                default:
                    val = $('#' + id).val(val); 
                }
            }
        },
        
        /**
         * 关闭表单容器。
         */
        close: function(){
            if(this.backdrop){this.backdrop.hide();}
            if(this.container){this.container.hide();}
            this.dispatchEvent(ModelForm.Event.CLOSED);
            this.reset();
        },
        
        /**
         * 显示当前表单。
         */
        show: function(){
            if(this.backdrop){this.backdrop.show();}
            if(this.container){this.container.show();}
            if(this.popup){
                var left = ($(window).width() - $(this.container).width())/2,
                    top = ($(window).height() - $(this.container).height())/2;
                left = (left > 0) ? left: 0;
                top = (left > 0) ? top: 0;
                $(this.container).css('left', left);
                $(this.container).css('top', top);
            }
        },
        
        /**
         * 删除表单容器。
         */
        remove: function(){
            if(this.backdrop){this.backdrop.remove();}
            if(this.container){this.container.remove();}
            if(this.placeholder){$(this.placeholder).html();}
        }
    });
    
    /**
     * 模型表单的类实例列表。
     */
    ModelForm.Instances = {};
    
    /**
     * 模型表单的事件。
     */
    ModelForm.Event = $.extend(Event, { 'LOADED': 'loaded', 'SUCCESS': 'success', 'CLOSED': 'closed' });
    
    exports.ModelForm = ModelForm;
});
