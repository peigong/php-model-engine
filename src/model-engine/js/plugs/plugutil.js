define(function(require, exports, module) {
    var $ = jQuery = require('jquery');
    require('bootstrap');
    
    /**
     * 创建表单项的水平容器。
     * @param {Element} container 表单项的容器。
     * @param {Object} settings 表单项的配置数据。
     * @param {String} name 表单对象的名称。
     * @param {String} title 表单对象标签的文本。
     */
    function createHorizontalContainer(containers, container, settings, name, title){
        var controlGroup = $('<div />');
        container.append(controlGroup);
        controlGroup.attr('class', 'control-group');
        controlGroup.prop('settings', settings);
        containers.push(controlGroup);
        
        var label = $('<label />');
        controlGroup.append(label);
        label.attr('class', 'control-label');
        if(name){ label.attr('for', name); }
        if(title){ label.html(title + '：'); }
        
        var controls = $('<div />');
        controlGroup.append(controls);
        controls.attr('class', 'controls');
        return controls;
    };
    
    /**
     * 创建表单项的内联容器。
     * @param {Element} container 表单项的容器。
     * @param {Object} settings 表单项的配置数据。
     * @param {String} name 表单对象的名称。
     * @param {String} title 表单对象标签的文本。
     */
    function createInlineContainer(containers, container, settings, name, title){
        var controlGroup = $('<div style="display:inline;" />');
        container.append(controlGroup);
        controlGroup.prop('settings', settings);
        containers.push(controlGroup);
        if(title){
            var label = $('<label style="display:inline;" />');
            controlGroup.append(label);
            label.attr('class', '');
            if(name){ label.attr('for', name); }
            if(title){ label.html(title + '：'); }
            var controls = $('<div style="display:inline;" />');
            controlGroup.append(controls);
            return controls;
        }
        return controlGroup;
    }

    exports.createInlineContainer = createInlineContainer;
    exports.createHorizontalContainer = createHorizontalContainer;
});
