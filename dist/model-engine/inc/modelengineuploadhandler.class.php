<?php
require_once(ROOT . 'inc/core/ioc/applicationcontext.inc.php');
require_once(jQueryFileUploadLibPath . 'UploadHandler.php');

/**
 * 模型引擎系统文件上传类。
 */
class ModelEngineUploadHandler extends UploadHandler implements IInjectEnable{
    /**
    * HTTP上下文对象。
    */
    private $context = null;
        
    /*- IInjectEnable 接口实现 START -*/
    /**
     * 设置属性值。
     */
    public function __set($prop, $val){
        $this->$prop = $val;
    }
    /*- IInjectEnable 接口实现 END -*/
    /**
     * 构造函数。
     */
    function  __construct($options = null, $error_messages = null){
        if(null == $options){
            $options = array();
        }
        $usr_upload_path = 'u/';
        $options = array_merge(
            array(
                'user_dirs' => true,
                'usr_upload_path' => $usr_upload_path,
                'upload_dir' => STATIC_PATH . '/uploads/' . $usr_upload_path,
                'upload_url' => STATIC_HOST . '/uploads/' . $usr_upload_path,
                'inline_file_types' => '/\.(gif|jpe?g|png|swf|flv)$/i',
                'image_versions' => array()
            ), $options);
        parent::__construct($options, false, $error_messages);
    }
    
    /**
     * 构造函数。
     */
    function ModelEngineUploadHandler(){
        $this->__construct();
    }
    
    public function receive() {
        $this->initialize();
    }

    protected function get_user_id() {
        $uid = -1;
        if ($this->context) {//定义了HTTP上下文对象，则以用户ID为依据上传。
            $principal = $this->context->getUser();
            $identity = $principal->getIdentity();
            $uid = $identity->getUserId();
        }else{//没有HTTP上下文对象，以其他逻辑上传

        }
        return $uid;
    }

    protected function get_user_path() {
        if ($this->options['user_dirs']) {
            $usr_id = $this->get_user_id();
            return ($usr_id % 10) . '/' . $usr_id . '/';
        }
        return '';
    }
    
    private function rename_upload_file($name){
        $result = $name;
        $arr_name = explode('.', $name);
        if(count($arr_name) > 0){
            $suffix = array_pop($arr_name);
            $result = implode('.', array(time(), $suffix));
        }
        return $result;
    }

    protected function get_save_path($file_name = null, $version = null) {
        $file_name = $file_name ? $file_name : '';
        $version_path = empty($version) ? '' : $version.'/';
        return $this->options['usr_upload_path'].$this->get_user_path()
            .$version_path.$file_name;
    }
    
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
            $index = null, $content_range = null) {
        $name = $this->rename_upload_file($name);
        $file = new stdClass();
        $file->name = $this->get_file_name($name, $type, $index, $content_range);
        $file->size = $this->fix_integer_overflow(intval($size));
        $file->type = $type;
        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $this->handle_form_data($file, $index);
            $upload_dir = $this->get_upload_path();
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, $this->options['mkdir_mode'], true);
            }
            $file_path = $this->get_upload_path($file->name);
            $append_file = $content_range && is_file($file_path) &&
                $file->size > $this->get_file_size($file_path);
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                move_uploaded_file($uploaded_file, $file_path);
            }
            $file_size = $this->get_file_size($file_path, $append_file);
            if ($file_size === $file->size) {
                $file->url = $this->get_download_url($file->name);
                $file->save_url = $this->get_save_path($file->name);
            } else if (!$content_range && $this->options['discard_aborted_uploads']) {
                unlink($file_path);
                $file->error = 'abort';
            }
            $file->size = $file_size;
        }
        return $file;
    }
}
?>
