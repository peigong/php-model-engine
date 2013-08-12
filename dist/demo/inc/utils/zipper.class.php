<?php
/**
 * 广告前端系统统一DEMO项目
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */

/**
 * 文件压缩类。
 * 使用本类，linux需开启 zlib，windows需取消php_zip.dll前的注释。
 */
class Zipper extends ZipArchive{
    /**
     * 向ZIP文件中添加目录或文件。
     * @param $path {String} 要添加的路径。 
     * @param $base {String} 不需要加入压缩文件的基础路径。 
     */
    function add($path, $base){
        if(strlen($path)> 0 && strlen($base)> 0){
            $src = implode('/', array($base, $path));
        }elseif(strlen($base)> 0){
            $src = $base;
        }
        if(is_file($src)){
            $this->addFile($src, $path);
        }elseif(is_dir($src)){
            if($dh = opendir($src)){
                while(false !== ($file = readdir($dh))){
                    if (strlen($path) > 0) {
                        $zip_path = implode('/', array($path, $file));
                    }else{
                        $zip_path = $file;
                    }
                    if($file != '.' && $file != '..' && $file != '.svn'){
                        $this->add($zip_path, $base);
                    }
                }
            }
        }
    }
}

/**
 * 输出压缩文件流。
 * @param $filename {String} 要输出的压缩文件的全路径名。
 */
function zip_output($filename){
    if(!file_exists($filename)){
        exit("无法找到文件");  
    }  
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    //文件名
    header('Content-disposition: attachment; filename='.basename($filename));
    //zip格式的
    header("Content-Type: application/zip");
    //告诉浏览 器，这是二进制文件
    header("Content-Transfer-Encoding: binary");
    //告诉浏览 器，文件大小
    header('Content-Length: '. filesize($filename));
    @readfile($filename);   
}
?>
