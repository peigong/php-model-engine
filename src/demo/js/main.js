
/*全局的JS入口*/
require.config({
    baseUrl: "/",
    paths: {
        'jquery': 'libs/jquery-2.0.3/jquery.min',
        'jquery.ui': 'libs/jquery-ui-1.10.3/jquery-ui.min',
        'bootstrap': 'js/bootstrap'
    },
    shim:{
    	'jquery.ui': ['jquery'],
    	'bootstrap': ['jquery']
    }
});

require(['js/util'], function(util){
    util.loadCss(require.toUrl('css/global.css'));
});
