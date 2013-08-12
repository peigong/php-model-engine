/**
 * 广告前端系统统一DEMO项目
 * 模型及模型表单引擎。
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */
define(function(require, exports, module) {
    /**
     * 事件定义。
     */
    var Event = {};
    
    /**
     * 事件分发器抽象类。
     */
    function EventDispatcher(){
        this.callbackDict = {};
    };
    EventDispatcher.prototype = {
        /**
         * 添加事件监听。
         * @param {Event} event 事件。
         * @param {Function} listenter 事件触发时执行的方法。
         */
        addEventListener: function(event, listenter){
            if(this.callbackDict.hasOwnProperty(event)){
                this.callbackDict[event].push(listenter);
            }else{
            }
        },

        /**
         * 分发事件。
         * @param {Event} event 事件。
         */
        dispatchEvent: function(event){
            var callback = this.callbackDict[event];
            if(callback){
                for(var i = 0; i < callback.length; i++){
                    callback[i]();
                }
            }
        },
        
        /**
         * 删除事件监听。
         * @param {Event} event 事件。
         */
        removeEventListener: function(event){
            if(this.callbackDict.hasOwnProperty(event)){
                delete this.callbackDict[event];
            }
        },
        
        /**
         * 当前类实例是否存在指定事件。
         * @param {Event} event 事件。
         * @param {Boolean} 是否存在指定事件。
         */
        hasEventListener: function(event){
            return this.callbackDict.hasOwnProperty(event);
        },
        
        /**
         * 当前类实例是否会触发指定事件。
         * @param {Event} event 事件。
         * @param {Boolean} 是否触指发定事件。
         */
        willTrigger: function(event){
            return this.callbackDict.hasOwnProperty(event);
        }
    };
    
    exports.Event = Event;
    exports.EventDispatcher = EventDispatcher;
});

