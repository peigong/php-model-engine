define([
    'jquery',
    'model-engine/js/enum',
    'model-engine/js/plugs/preview',
    'model-engine/js/util',
    'model-engine/js/plugs/plugutil',
    'jquery-file-upload/jquery.fileupload'
], function($, enu, pre, util, plugutil) {
    var ModelType = enu.ModelType,
        FormfieldPrefix = enu.FormfieldPrefix,
        preview = pre.preview,
        loadCss = util.loadCss;
        
    loadCss(require.toUrl('bootstrap-css'));
    loadCss(require.toUrl('jquery-file-upload/jquery.fileupload-ui.css'));    
        
    /**
     * 创建文件上传控件表单项。
     * @param o {ModelForm} 模型表单对象的实例。
     *      o.containers {Array} 表单项的容器的队列。
     *      o.controls {Object} 表单项的字典。
     * @param container {Element} 表单项的容器。
     * @param settings {Object} 表单项的配置数据。
     * @param ext {Object} 扩展配置。
     * @param def {String} 表单对象的默认值。
     */
    function create(o, container, settings, ext, def){
        var attributes = settings.attributes,
            form_name = o.getControlName(settings),
            controls = plugutil.createHorizontalContainer(o.containers, container, settings, form_name, attributes.label);
        
        var upload_service = './svr/file_upload.php';
        if (ext.hasOwnProperty('upload_service')) {
            upload_service = ext['upload_service'];
        };

        var txt = $('<input />');
        controls.append(txt);
        txt.attr('id', form_name);
        txt.attr('name', form_name);
        txt.attr('class', 'file');
        txt.attr('type', 'text');
        txt.attr('placeholder', attributes.placeholder);
        var btn_group = $('<div class="btn-group"></div>')
        controls.append(btn_group);
        attributes.buttontext = attributes.buttontext || '上传';
        var span = $('<span class="btn btn-success fileinput-button" style="margin-right:0px;"><i class="icon-plus icon-white"></i><span>' + attributes.buttontext + '</span></span>');           
        btn_group.append(span);
        var arrow = $('<a class="btn btn-success" href="javascript:void(0);"><i class="icon-arrow-up icon-white"></i></a>');           
        btn_group.append(arrow);
        var progress_container = $('<div class="progress progress-success progress-striped" style="display:none;"><div class="bar"></div></div>');
        controls.append(progress_container);
        var preview_container = $('<div style="display:none;"></div>');
        controls.append(preview_container);
        function preview_toggle(){
            $(this).find('i').toggleClass('icon-arrow-up');
            $(this).find('i').toggleClass('icon-arrow-down');
            $(preview_container).toggle();
        }
        $(arrow).click(preview_toggle);
        var file = $('<input />');
        span.append(file);
        file.prop('o', this);
        file.attr('id', 'fileupload_' + form_name);
        file.attr('name', 'files[]');
        file.attr('type', 'file');
        var oThis = this;
        $(file).fileupload({
            url: upload_service,
            autoUpload: true,
            dataType: 'json',
            done: function (e, data) {
                var url, save_url;
                $.each(data.result.files, function (index, file) {
                    url = file.url;
                    save_url = file.save_url;
                    $(txt).val(save_url);
                });
                //打开预览
                $(progress_container).hide();
                preview($(preview_container), url);
                $(preview_container).show();
                $(arrow).find('i').removeClass('icon-arrow-up');
                $(arrow).find('i').addClass('icon-arrow-down');
                window.setTimeout(function(){
                    $(preview_container).hide();
                    $(arrow).find('i').addClass('icon-arrow-up');
                    $(arrow).find('i').removeClass('icon-arrow-down');
                }, 3e3)
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $(progress_container).find('.bar').css(
                    'width',
                    progress + '%'
                );
            }
        });
        $(file).bind('fileuploadadd', function (e, data) {
            $(progress_container).show();
        })

        if (def) {
            $(txt).val(def);
        };
        o.controls[form_name] = { 'id': form_name, 'name': settings.name, 'type': ModelType.FILEINPUT, 'field': attributes.field };
    }
    
    return {
        'create': create
    };
});
