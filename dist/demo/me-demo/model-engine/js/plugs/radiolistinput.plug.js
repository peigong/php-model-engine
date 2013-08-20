define([
    'jquery',
    'model-engine/js/enum'
], function($, enu) {
    var ModelType = enu.ModelType,
        FormfieldPrefix = enu.FormfieldPrefix;
                
    /**
     * 创建单选按钮列表表单项。
     * @param o {ModelForm} 模型表单对象的实例。
     *      o.containers {Array} 表单项的容器的队列。
     *      o.controls {Object} 表单项的字典。
     * @param container {Element} 表单项的容器。
     * @param settings {Object} 表单项的配置数据。
     * @param ext {Object} 扩展配置。
     * @param def {String} 表单对象的默认值。
     */
    function create(o, container, settings, ext, def){
        var util = require('model-engine/js/plugs/plugutil'),
            attributes = settings.attributes,
            items = settings.items, 
            form_name = o.getControlName(settings),
            controls = util.createHorizontalContainer(o.containers, container, settings, form_name, attributes.label);
            
        for(var i = 0; i < items.length; i++){
            var lbl_rdl = $('<label class="radio inline">');
            controls.append(lbl_rdl);
            var input = $('<input type="radio" />');
            lbl_rdl.append(input);
            input.attr('name', form_name);
            input.attr('value', items[i]['value']);
            lbl_rdl.append(items[i]['text']);
        }
        
        if (def) {
            $('input[name=' + form_name + ']').attr("checked", def);
        };
        o.controls[form_name] = {'id': form_name, 'name': settings.name, 'type': ModelType.RADIOLISTINPUT, 'field': attributes.field};
    }
    
    return {
        'create': create
    };
});
