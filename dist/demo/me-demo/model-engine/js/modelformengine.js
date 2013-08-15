define([
    'model-engine/js/modelform'
], function(mf) {
    var ModelForm = mf.ModelForm;
        
    /**
     * 模型表单引擎。
     */
    function ModelFormEngine(){}
    ModelFormEngine.prototype = {
        /**
         * 创建模型表单类实例。
         * @param id {String} 模型表单的ID。
         * @param name {String} 模型表单的名称。
         * @param options {Object} 表象的可选项。
         * @param def {Object} 表单的默认值对象。
         * @return {ModelForm} 模型表单类。
         */
        create: function(id, name, options, def){
            if(!ModelForm.Instances.hasOwnProperty(id)){        
                var form = new ModelForm(id, name, options, def);
                ModelForm.Instances[id] = form;
                var data = {'name': name};
                if(options && options['init_key'] && ('id' == options['init_key'])){
                    //表单ID
                    data = {'id': id};
                }
                if(options && options['model'] && (options['model'].length > 0)){
                    //表单宿主的模型
                    data['model'] = options['model'];
                }
                form.load(data);
            }
            return ModelForm.Instances[id];
        },
        
        /**
         * 清空模型表单队列。
         */
        clean: function(){
            for(var id in ModelForm.Instances){
                ModelForm.Instances[id].remove();
                delete ModelForm.Instances[id];
            }
            ModelForm.Instances = {};
        }
    };
    
    /**
     * 模型表单引擎的实例列表。
     */
    ModelFormEngine.Instances = {};
    
    /**
     * 根据ID，获取模型表单引擎的实例。
     * @param id {String} 模型表单引擎的ID。
     * @return {ModelFormEngine} 模型表单引擎的实例。
     */
    ModelFormEngine.getInstance = function(id){
        id = id || 'global';
        if(!ModelFormEngine.Instances.hasOwnProperty(id)){        
            ModelFormEngine.Instances[id] = new ModelFormEngine();
        }
        return ModelFormEngine.Instances[id];
    };
    
    return {
        'ModelFormEngine': ModelFormEngine
    };
});
