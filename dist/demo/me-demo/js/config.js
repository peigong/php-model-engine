
/*模型和表单引擎特定的JS配置*/
/*seajs.config({
    paths: {
        'model-engine': 'me-demo/model-engine',
        'jquery.validate': '/libs/jquery-validation-1.11.1'
    }
});*/
seajs.config({
	base: '/',
    alias: {
        'bootstrap': 'libs/bootstrap-v3.0.0-rc1/js/bootstrap.min.js',
        'jquery.ui': 'libs/jquery-ui-1.10.3/jquery-ui.min.js',
        'jquery': 'libs/jquery-2.0.3/jquery.min.js'
    },
    paths: {
        'bootstrap-css': 'libs/bootstrap-v3.0.0-rc1/css',
        'model-engine': 'me-demo/model-engine',
        'jquery.validate': '/libs/jquery-validation-1.11.1'
    }
});
