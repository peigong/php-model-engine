
/*全局的JS入口*/
require.config({
    baseUrl: "/",
    paths: {
        'jquery': 'libs/jquery-2.0.3/jquery.min',
        'jquery.ui': 'libs/jquery-ui-1.10.3/jquery-ui.min',
        'bootstrap': 'libs/bootstrap-v2.3.2/js/bootstrap.min',
        'bootstrap-css': 'libs/bootstrap-v2.3.2/css/bootstrap.min.css',
        'bootstrap-responsive-css': 'libs/bootstrap-v2.3.2/css/bootstrap-responsive.min.css'
    },
    shim:{
    	'jquery.ui': ['jquery'],
    	'bootstrap': ['jquery']
    }
});

function loadCss(url) {
    var link = document.createElement("link");
    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = url;
    document.getElementsByTagName("head")[0].appendChild(link);
}

loadCss(require.toUrl('bootstrap-css'));
loadCss(require.toUrl('bootstrap-responsive-css'));
loadCss(require.toUrl('css/global.css'));