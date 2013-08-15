define([
    'jquery',
    'model-engine/js/event'
], function($, eve) {
    var Event = eve.Event,
        EventDispatcher = eve.EventDispatcher;
        
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
    
    return {
        'empty': empty,
        'List': List
    };
});

