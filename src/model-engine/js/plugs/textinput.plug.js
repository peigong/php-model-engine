define([
    'jquery',
    'model-engine/js/enum',
    'model-engine/js/util',
    'model-engine/js/plugs/plugutil',
    'model-engine/js/plugs/triggeredtextinputeditor.plug'
],function($, enu, util, plugutil, trigger) {
    var ModelType = enu.ModelType,
        FormfieldPrefix = enu.FormfieldPrefix,
        loadCss = util.loadCss;
        
    loadCss(require.toUrl('bootstrap-css'));
        
    /**
     * 创建单行文本框表单项。
     * @param o {ModelForm} 模型表单对象的实例。
     *      o.containers {Array} 表单项的容器的队列。
     *      o.controls {Object} 表单项的字典。
     * @param container {Element} 表单项的容器。
     * @param settings {Object} 表单项的配置数据。
     * @param ext {Object} 扩展配置。
     * @param def {String} 表单对象的默认值。
     */
    function create(o, container, settings, ext, def){
        var attributes = settings.attributes,
            triggered = attributes['triggered'] * 1,
            inline = ext['inline'],
            form_name = o.getControlName(settings),
            controls;
        if (!!def && triggered) {
            if(trigger.create){
                trigger.create(o, container, settings, ext, def);
            }
        }else{
            var input = $('<input />');
            input.attr('id', form_name);
            input.attr('name', form_name);
            if (attributes['password'] * 1) {
                input.attr('type', 'password');
            }else{
                input.attr('type', 'text');
            };
            input.attr('placeholder', attributes.placeholder);
            if(inline){
                input.attr('style', 'display:inline;');
                controls = plugutil.createInlineContainer(o.containers, container, settings, form_name, attributes.label)
            }else{
                controls = plugutil.createHorizontalContainer(o.containers, container, settings, form_name, attributes.label)
            }
            if(attributes.size){
                input.addClass(attributes.size);
            }
            if(attributes.prepend || attributes.append){
                var box = $('<div />');
                controls.append(box);
                if(attributes.prepend){
                    box.addClass('input-prepend');
                    box.append($('<span class="add-on">' + attributes.prepend + '</span>'));
                }
                box.append(input);
                if(attributes.append){
                    box.addClass('input-append');
                    box.append($('<span class="add-on">' + attributes.append + '</span>'));
                }
            }else{
                controls.append(input);
            }
            if (def) {
                $(input).val(def);
            };
            o.controls[form_name] = { 'id': form_name, 'name': settings.name, 'type': ModelType.TEXTINPUT, 'field': attributes.field };
        }
    }
    
    return {
        'create': create
    };
});
