/**
 * 广告前端系统统一DEMO项目
 * 模型及模型表单引擎。
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */
define(function(require, exports, module) {
    /**
     * 创建表单行容器。
     * @param o {ModelForm} 模型表单对象的实例。
     *      o.containers {Array} 表单项的容器的队列。
     *      o.controls {Object} 表单项的字典。
     * @param container {Element} 表单项的容器。
     * @param settings {Object} 表单项的配置数据。
     * @param ext {Object} 扩展配置。
     * @param def {String} 表单对象的默认值。
     */
    function create(o, container, settings, ext, def){
        var util = require('/model-engine/js/plugs/plugutil'),
            attributes = settings.attributes,
            items = settings.items,
            createItem = ext['createItem'],
            controls = util.createHorizontalContainer(o.containers, container, settings, '', attributes.title);
        if(items){
            for(var i = 0; i < items.length; i++){
                createItem(controls, items[i], true);
            }
        }
    }
    
    exports.create = create;
});
