define(function() {
    /**
     * 格式化对象。
     * @param str {String} 用于格式化的字符串。
     * @param o {Object} 要格式化的对象。
     */
    function format(str, o){
        var result = '';
        if(str.indexOf('{') < 0){
            result = o[str] || '';
        }else{
            result = str.replace(/\{\s*(\w+)\s*\}/g, function(m, key){
                    return o[key];
                });
        }
        return result;
    }

    function loadCss(url) {
        var link = document.createElement("link");
        link.type = "text/css";
        link.rel = "stylesheet";
        link.href = url;
        document.getElementsByTagName("head")[0].appendChild(link);
    }

    return {
        'format': format,
        'loadCss': loadCss
    };
});
