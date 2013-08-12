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
        ModelType = enu.ModelType,
        FormfieldPrefix = enu.FormfieldPrefix;
        
    require('bootstrap');
        
    /**
     * 创建复选按钮表单项。
     * @param o {ModelForm} 模型表单对象的实例。
     *      o.containers {Array} 表单项的容器的队列。
     *      o.controls {Object} 表单项的字典。
     * @param container {Element} 表单项的容器。
     * @param settings {Object} 表单项的配置数据。
     * @param ext {Object} 扩展配置。
     * @param func {Function} 处理下一级表单项的配置数据的回调函数。
     * @param def {String} 表单对象的默认值。
     */
    function create(o, container, settings, ext, def){
        var util = require('/model-engine/js/plugs/plugutil'),
            attributes = settings.attributes,
            form_name = o.getControlName(settings),
            controls = util.createHorizontalContainer(o.containers, container, settings, form_name, attributes.label);
                
        var lbl_chb = $('<label class="checkbox">');
        controls.append(lbl_chb);
        var input = $('<input />');
        lbl_chb.append(input);
        input.attr('id', form_name);
        input.attr('name', form_name);
        input.attr('type', 'checkbox');
        lbl_chb.append(settings.description);
        
        if (def) {
            $(input).prop('checked', !!(def * 1));
        };
        o.controls[form_name] = { 'id': form_name, 'name': settings.name, 'type': ModelType.CHECKBOXINPUT, 'field': attributes.field };
    }
    
    exports.create = create;
});
