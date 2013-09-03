define([
    'jquery',
    'model-engine/js/enum'
], function($, enu) {
    var ModelType = enu.ModelType,
        FormfieldPrefix = enu.FormfieldPrefix;
        
    /**
     * 创建下拉列表表单项。
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
            list = settings.list, 
            form_name = o.getControlName(settings),
            controls = util.createHorizontalContainer(o.containers, container, settings, form_name, attributes.label);
        
        var input = $('<select />');
        controls.append(input);
        input.attr('id', form_name);
        input.attr('name', form_name);
        o.controls[form_name] = {'id': form_name, 'name': settings.name, 'type': ModelType.SELECTINPUT, 'field': attributes.field};

        if (list && ext.hasOwnProperty('list_service')) {
            var options = {
                'type': list['type'], 
                'id': list['id'],
                /*宿主模型的用途主要是在特定情况下获取模型字段的列表*/
                'parasitifer': ext['parasitifer']
            };
            $.getJSON(ext['list_service'], options, function(items){
                var group = input;
                for(var i = 0; i < items.length; i++){
                    if(items[i]['group']){
                        group = $('<optgroup label=\'' + items[i]['label'] + '\' />');
                        input.append(group);
                    }else{
                        group.append($('<option value=\'' + items[i]['value'] + '\'>' + items[i]['text'] + '</option>'));
                    }
                }

                if (def) {
                    $(input).val(def);
                };
            });
        };
    }
    
    return {
        'create': create
    };
});
