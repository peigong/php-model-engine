/**
 * 广告前端系统统一DEMO项目
 * 模型及模型表单引擎。
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */
define(function(require, exports, module) {
    var $ = jQuery = require('jquery'),
        e = require('/model-engine/js/event'),
        Event = e.Event,
        EventDispatcher = e.EventDispatcher;
        
     var empty = '--';
    
    /**
     * 列表抽象类。
     */
    function List(){
        EventDispatcher.call(this);
        this.items = [];
        this.selectedIndex = -1;
        this.selectedValue = empty;
        this.selectedItem = {};
    };
    $.extend(List.prototype, EventDispatcher.prototype, {
        /**
         * 装载列表。
         */
        load: function(){}
    });
    
    /**
     * 列表的事件。
     */
    List.Event = $.extend(Event, {'CHANGE': 'change'});
    
    exports.empty = empty;
    exports.List = List;
});

