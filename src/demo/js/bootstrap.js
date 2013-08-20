define([
    'jquery',
    'js/util'
], function($, util) {
    require(['libs/bootstrap-v2.3.2/js/bootstrap.min'], function(){});
    util.loadCss(require.toUrl('libs/bootstrap-v2.3.2/css/bootstrap.min.css'));
    util.loadCss(require.toUrl('libs/bootstrap-v2.3.2/css/bootstrap-responsive.min.css'));
});
