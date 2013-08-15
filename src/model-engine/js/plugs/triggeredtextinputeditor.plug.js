define([
    'jquery',
    'model-engine/js/enum',
    'model-engine/js/util'
],function($, enu, util, plugutil) {
    var ModelType = enu.ModelType,
        FormfieldPrefix = enu.FormfieldPrefix,
        loadCss = util.loadCss;
        
    loadCss(require.toUrl('bootstrap-css'));
        
    /**
     * 创建由点击触发的单行文本框编辑器表单项。
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
            form_name = o.getControlName(settings);

        var controlGroup = $('<div />');
        container.append(controlGroup);
        controlGroup.attr('class', 'control-group');
        controlGroup.prop('settings', settings);
        o.containers.push(controlGroup);
        
        var label = $('<label />');
        controlGroup.append(label);
        label.attr('class', 'control-label');
        if(form_name){ label.attr('for', form_name); }
        if(attributes.label){ label.html(attributes.label + '：'); }
        
        var controls = $('<div style="display:none;" />');
        controlGroup.append(controls);
        controls.attr('class', 'controls');

        var input = $('<input />');
        input.attr('id', form_name);
        input.attr('name', form_name);
        input.attr('type', 'text');
        input.attr('placeholder', attributes.placeholder);
        if(attributes.size){
            input.addClass(attributes.size);
        }
        $(input).val(def);
        controls.append(input);

        var showContainer = $('<div />');
        controlGroup.append(showContainer);
        showContainer.attr('class', 'controls');

        var span = $('<span />');
        span.html(def);
        showContainer.append(span);
        
        edit = $('<a title="" href="#"><i class="icon-edit"></i></a>');
        edit.prop('show', showContainer);
        edit.prop('edit', controls);
        edit.click(function(){
            var show = $(this).prop('show'),
                edit = $(this).prop('edit');
            $(show).hide();
            $(edit).show();
        });
        showContainer.append(edit);

        o.controls[form_name] = { 'id': form_name, 'name': settings.name, 'type': ModelType.TEXTINPUT, 'field': attributes.field };
    }
    
    return {
        'create': create
    };
});
