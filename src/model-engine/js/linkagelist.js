define(function(require, exports, module) {
    require.async([
        'jquery',
        'model-engine/js/list'
    ],function($, l){
        var List = l.List;
            
        /**
         * 联动列表。
         * @param master {ModelList} 主模型列表。
         * @param slave {ModelList} 从模型列表。
         * @param options {Object} 模型列表的可选项。
         */
        function LinkageList(master, slave, options){
            this.master = master;
            this.slave = slave;
            this.options = options;
            this.initialise();
        };
        LinkageList.prototype = {
            /**
             * 初始化。
             */
            initialise: function(){
                this.master.addEventListener(List.Event.CHANGE, $.proxy(this.change, this));
            },
            
            load: function(){
                this.master.load();
            },
            
            change: function(){
                var code = this.master.selectedValue;
                this.slave.load(code);
            }
        };      
        exports.LinkageList = LinkageList;
    });
});

