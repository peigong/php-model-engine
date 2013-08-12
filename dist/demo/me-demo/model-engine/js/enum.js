/**
 * 广告前端系统统一DEMO项目
 * 模型及模型表单引擎。
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */
define(function(require, exports, module) {
    /**
     * 模型的类型。
     */
    var ModelType = {
            /**
             * 模型表单。
             */
            'MODELFORM': 'modelform',
            
            /**
             * 表单标签容器。
             */
            'TABCONTAINER': 'tabcontainer',
            
            /**
             * 表单行容器。
             */
            'ROWCONTAINER': 'rowcontainer',
            
            /**
             * 单行文本框。
             */
            'TEXTINPUT': 'textinput',
            
            /**
             * 下拉列表。
             */
            'SELECTINPUT': 'selectinput',
            
            /**
             * 复选按钮。
             */
            'CHECKBOXINPUT': 'checkboxinput',
            
            /**
             * 复选按钮列表。
             */
            'CHECKBOXLISTINPUT': 'checkboxlistinput',
            
            /**
             * 单选按钮列表。
             */
            'RADIOLISTINPUT': 'radiolistinput',
            
            /**
             * 文件上传控件。
             */
            'FILEINPUT': 'fileinput',
            
            /**
             * 模型。
             */
            'MODEL': 'model',
            
            /**
             * 模型属性。
             */
            'ATTRIBUTE': 'attribute',
            
            /**
             * 系统内置列表。
             */
            'SYSTEMLIST': 'systemlist',
            
            /**
             * 用户自定义列表。
             */
            'CUSTOMLIST': 'customlist',
            
            /**
             * 用户自定义列表项。
             */
            'CUSTOMLISTITEM': 'customlistitem',
            
            /**
             * 模型表单的验证数据对象。
             */
            'VALIDATION': 'validation'
        };
        
    /**
     * 模型的类别。
     */
    var ModelCategory = {
        'FORMINPUT': 2,
        'AD': 3
    };
    
    var FormfieldPrefix = {};
    FormfieldPrefix[ModelType.TEXTINPUT] = 'txt_';
    FormfieldPrefix[ModelType.CHECKBOXINPUT] = 'chb_';
    FormfieldPrefix[ModelType.FILEINPUT] = 'fil_';
    FormfieldPrefix[ModelType.RADIOLISTINPUT] = 'rdl_';
    FormfieldPrefix[ModelType.SELECTINPUT] = 'ddl_';
    
    exports.ModelType = ModelType;
    exports.FormfieldPrefix = FormfieldPrefix;
    exports.ModelCategory = ModelCategory;
});

