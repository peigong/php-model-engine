/**
 * 广告前端系统统一DEMO项目
 * 模型及模型表单引擎。
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */
define(function(require, exports, module) {
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

    exports.format = format;
});
