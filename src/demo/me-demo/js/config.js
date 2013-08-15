
/*模型和表单引擎特定的JS配置*/
require.config({
    paths: {
        'model-engine': 'me-demo/model-engine',
        'jquery-validate': 'libs/jquery-validation-1.11.1',
        'jquery-file-upload': 'libs/jquery-file-upload-8.7.1'
    },
    shim:{
    	'jquery-validate/jquery.validate.min': ['jquery', 'jquery.ui'],
    	'jquery-validate/localization/messages_zh': ['jquery', 'jquery.ui', 'jquery-validate/jquery.validate.min']
    }
});