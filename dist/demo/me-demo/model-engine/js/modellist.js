define(function(require, exports, module) {
    require('model-engine/css/model-engine.css');
    require.async([
        'jquery',
        'model-engine/js/util',
        'model-engine/js/list'
    ],function($){
        var format = util.format,
            empty = l.empty;
            List = l.List;
            
         
        /**
         * 模型列表。
         * @param container {String} 模型列表的容器。
         * @param type {String} 模型的编码。
         * @param options {Object} 模型列表的可选项。
         */
        function ModelList(container, type, options){
            List.call(this);
            this.code = '';
            this.model = '';
            this.list = [];
            this.type = type;
            this.ul = null;
            this.container = $(container);
            this.callbackDict = $.extend(this.callbackDict, {'change': []});
            this.options = $.extend({ 
                'init_model': '',
                'text': 'name', 'value': 'code', 
                'service': '/model-engine/svr/model_list_fetch.php',
                'extparams': {}
            }, options);
            if (this.options['init_model']) {
                this.model = this.options['init_model'];
            };
        };
        $.extend(ModelList.prototype, List.prototype, {
            /**
             * 加载模型列表。
             * @param code {String} 需要加载的列表编码（用于请求服务器端的列表数据）。
             */
            load: function(code){
                code = code || this.code;
                if(empty == code){
                    this.loadSuccess([]);
                }else{
                    var options = { 't': this.type };
                    options['code'] = this.code = code;
                    /*- 扩展参数 -*/
                    var ext_params = this.options['extparams'];
                    if(ext_params){
                        for(var key in ext_params){
                            options[key] = ext_params[key];
                        }
                    }
                    if(this.model){//扩展参数，列表所关联的模型
                        options['model'] = this.model;
                    }
                    /*- 扩展参数 -*/
                    if(this.options.service && this.options.service.length){
                        $.getJSON(this.options.service, options, $.proxy(this.loadSuccess, this));
                    }
                }
            },
            
            /**
             * 请求服务器成功后执行的回调函数。
             * @param items {Array} 服务器端返回的列表数据。
             */
            loadSuccess: function(items){
                this.container.html('');
                if(items.length > 0){
                    this.list = [];
                    this.items = items;
                    var code, item, ul, li, text;
                    ul = $('<ul class="mode-list" />');
                    this.ul = ul;
                    this.container.append(ul);
                    for(var i = 0; i < items.length; i++){
                        item = items[i];
                        li = $('<li />');
                        ul.append(li);
                        li.attr('idx', i);
                        li.prop('o', this);
                        li.click(function(){
                                var o = $(this).prop('o'),
                                    idx = $(this).attr('idx');
                                ($.proxy(o.change, o))(idx);
                            });
                        text = $('<span />');
                        text.html(format(this.options['text'], item));
                        li.append(text);
                        this.list.push(li);
                        this.appendToListItem(li, i);
                    }
                    this.change(0, this);
                }else{
                    this.selectedIndex = -1;
                    this.selectedItem = {};
                    this.selectedValue = empty;
                    this.dispatchEvent(ModelList.Event.CHANGE);
                }
            },
                    
            /**
             * 向列表项中追加内容。
             * @param li {jQuery} 列表项对象。
             */
            appendToListItem: function(li, idx){},
            
            /**
             * 更改列表的选中项。
             * @param idx {Int} 当前项的索引。
             */
            change: function(idx){
                var li;
                for(var i = 0; i < this.list.length; i++){
                    li = this.list[i];
                    li.removeClass('active');
                }
                this.selectedIndex = idx;
                this.selectedItem = this.items[idx];
                this.selectedValue = this.items[idx][this.options['value']];
                this.list[idx].addClass('active');
                this.dispatchEvent(ModelList.Event.CHANGE);
            }
        });

        /**
         * 模型列表的事件。
         */
        ModelList.Event = $.extend(List.Event, {});
        
        exports.ModelList = ModelList;
    });
});
