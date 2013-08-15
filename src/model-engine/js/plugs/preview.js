define([
    'jquery'
], function($) {
    /**
     * 创建FLASH的HTML内容。
     * @param url {String} swf格式物料或播放器的URL地址。
     * @param width {Number} 宽度。
     * @param height {Number} 高度。
     * @param parameter {String} 传递的FlashVars
     */
    function createFlash(url, width, height, parameter) {
        parameter = parameter ? parameter : '';
        var flash =  '<embed src="' + url + '" FlashVars="' + parameter + '" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="' + width + '" height="' + height + '" wmode="transparent" allowfullscreen="false" allowscriptaccess="always"></embed>';
        return flash;
    };
    
    function createImage(url, width, height){
        var image = '<img src="' + url + '" width="' + width + '" height="' + height + '" border="0" />';
        return image;
    };
    
    function createFlv(url, width, height){
        var params = '';
        params += 'params=<cfg>';
        params += '<idx>0</idx>';
        params += '<materialURL>' + url + '</materialURL>';
        params += '<player_width>' + width + '</player_width>';
        params += '<player_height>' + height + '</player_height>';
        params += '<click_link>ss</click_link>';
        params += '</cfg>';
        var path = require.toUrl('model-engine/assets') + '/flvplayer.2.2.1.0.swf', 
            flv = createFlash(path, width, height, params);
        return flv;
    };
    
    function preview(container, url){
        var html,
            width = 150,
            height = 80,
            idx = url.lastIndexOf('.'),
            suffix = url.substring(idx + 1).toLowerCase();
        if ('swf'.indexOf(suffix) > -1) {
            html = createFlash(url, width, height);
        } else if ('jpg,jpeg,png,bmp,gif'.indexOf(suffix) > -1) {
            html = createImage(url, width, height);
        } else if('flv'.indexOf(suffix) > -1){
            html = createFlv(url, width, height);
        }
        $(container).html(html);
    };
    
    return {
        'preview': preview
    };    
});
