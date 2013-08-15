
/*全局的JS入口*/
require.config({
    baseUrl: "/",
    paths: {
        'jquery': 'libs/jquery-2.0.3/jquery.min.js',
        'jquery.ui': 'libs/jquery-ui-1.10.3/jquery-ui.min.js',
        'bootstrap': 'libs/bootstrap-v3.0.0-rc1/js/bootstrap.min.js'
        'bootstrap-css': 'libs/bootstrap-v3.0.0-rc1/css/bootstrap.css',

        'model-engine': 'me-demo/model-engine',
        'jquery-validate': 'libs/jquery-validation-1.11.1',
        'jquery-file-upload': 'libs/jquery-file-upload-8.7.1'
    },
    shim:{
    	'jquery.ui': ['jquery'],
    	'bootstrap': ['jquery']
    }
});

require.toUrl('bootstrap-css');    
require.toUrl('css/global.css');
require(['jquery'], function(){});