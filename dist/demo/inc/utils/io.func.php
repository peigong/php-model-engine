<?php
/**
 * 广告前端系统统一DEMO项目
 * 当前版本：@MASTERVERSION@
 * 构建时间：@BUILDDATE@
 * @COPYRIGHT@
 */
/**
 * 创建连续的目录。
 * @param String $dir 目录字符串。
 * @param Integer $mode 目录模式。
 */
function io_mkdir($dir, $mode = "777") {
    if (!$dir){return false;}
    $mdir = array();
    $dir = str_replace("\\", "/", $dir);
    foreach (explode("/", $dir) as $idx=>$val) {
        //$mdir .= $val . "/";
        if("." == $val){//当前相对目录，跳过
            continue;
        }elseif($val == ".."){//向上查找一级目录
            if(0 == $idx){
                //如果以..开头，则从当前工作目录向上查找一个层次
                $temp_dir = getcwd();
                $temp_dir = str_replace("\\", "/", $temp_dir);
                $mdir = explode("/", $temp_dir);
            }
            array_pop($mdir);
            continue;
        }
        if(0 == strlen($val)){
            continue;
        }else{
            array_push($mdir, $val);
            $dir_path = implode('/', $mdir);
            if('/' == $dir[0]){
                //初始目录第一个字符是'/'，在需要创建的目录前面加上'/'[LINUX]
                $dir_path = '/' . $dir_path;
            }
            if (!file_exists($dir_path)) {
                if(@mkdir($dir_path, $mode)) {
                    system(implode(' ', array('/bin/chmod', $mode, $dir_path)));
                }else{
                    return false;
                    exit;
                }
            }
        }
    }
    return true;
}

/**
 * 复制文件或目录（不复制svn信息）。
 * TODO:只支持目录复制，不支持通配符。
 * @param $src {String} 源文件或源目录。
 * @param $dest {String} 目标文件或目标目录。
 * @param $fileset {Array} 需要复制的文件列表描述。
 */
function io_copy($src, $dest, $fileset = array('include' => array(), 'exclude' => array())){
    $fileset = _utils_io_init_fileset($fileset);
    /*- 下一轮复制的路径列表 -*/
    $next_fileset = array('include' => array(), 'exclude' => array());
    $temp_next_include_files = array();
    $temp_next_exclude_files = array();
    $need_next_include_files = array();
    /*- 本轮复制需要包含和排除的路径列表 -*/
    $include_files = array();
    $exclude_files = array();
    
    /*- 审查本轮复制的包含和排除列表，同时准备一下轮复制包含和排除的备选列表 -*/
    $fileset_include = $fileset['include'];
    $fileset_exclude = $fileset['exclude'];
    for($i = 0; $i < count($fileset_include); $i++){
        $arr_file_name = _utils_io_explode_path($fileset_include[$i]['name']);
        /*- 弹出第一级路径（即本轮复制需要复制的路径） -*/
        $file_name = array_shift($arr_file_name);
        array_push($include_files, $file_name);
        /*- 如果弹出第一级路径后，还有路径层次，
            暂时进入下一轮复制可能包含的清单中，
            如果弹出的路径包括在下一轮复制的目录中，才能进入一下轮复制的包含列表 -*/
        if(count($arr_file_name) > 0){
            array_push($temp_next_include_files, array('current' => $file_name, 'next' => implode('/', $arr_file_name)));
        }
    }
    for($i = 0; $i < count($fileset_exclude); $i++){
        $arr_file_name = _utils_io_explode_path($fileset_exclude[$i]['name']);
        /*- 弹出第一级路径 -*/
        $file_name = array_shift($arr_file_name);
        if(count($arr_file_name) > 0){
            /*- 如果弹出第一级路径后，还有路径层次，
                暂时进入下一轮复制可能排除的清单中，
                如果弹出的路径包括在下一轮复制的目录中，才能进入一下轮复制的排除列表 -*/
            array_push($temp_next_exclude_files, array('current' => $file_name, 'next' => implode('/', $arr_file_name)));
        }else{
            /*- 如果弹出第一次路径后，没有了路径层次，则弹出的路径是本轮复制需要排除的路径） -*/
            array_push($exclude_files, $file_name);
        }
    }
    
    /*- 复制目录 -*/
    if(is_dir($src)){
        io_mkdir($dest);
        if($dh = opendir($src)){
            while(false !== ($file = readdir($dh))){
                $is_copy = true;
                if(count($include_files) > 0){//有包含列表，检查是否可以复制。
                    $is_copy = in_array($file, $include_files);
                }
                $is_copy = $is_copy && (!in_array($file, $exclude_files));
                if($is_copy){
                    $src_path = implode('/', array($src, $file));
                    $dest_path = implode('/', array($dest, $file));
                    if(is_file($src_path)){
                        copy($src_path, $dest_path);
                    }elseif(is_dir($src_path) && $file != '.' && $file != '..' && $file != '.svn'){
                        array_push($need_next_include_files, $file);
                    }
                }
            }
        }
        /*- 检查包含或排除的备选列表，是否包含在一下轮的复制列表中 -*/
        for($i = 0; $i < count($temp_next_include_files); $i++){
            if(in_array($temp_next_include_files[$i]['current'], $need_next_include_files)){
                array_push($next_fileset['include'], array('name' => $temp_next_include_files[$i]['next']));
            }
        }
        for($i = 0; $i < count($temp_next_exclude_files); $i++){
            if(in_array($temp_next_exclude_files[$i]['current'], $need_next_include_files)){
                array_push($next_fileset['exclude'], array('name' => $temp_next_exclude_files[$i]['next']));
            }
        }
        /*- 执行下一轮复制 -*/
        for($i = 0; $i < count($need_next_include_files); $i++){
            $next_src_path = implode('/', array($src, $need_next_include_files[$i]));
            $next_dest_path = implode('/', array($dest, $need_next_include_files[$i]));
            io_copy($next_src_path, $next_dest_path, $next_fileset);
        }
    }
    if(is_file($src)){
        if(is_dir($dest)){
            /*- 如果目标路径是个已经存在的目录，则将源文件复制到这个目录中 -*/
            $arr_src = _utils_io_explode_path($src);
            $file_name = array_pop($arr_dest);
            copy($src, implode('/', array($dest, $file_name)));
        }elseif(is_file($dest)){
            /*- 如果目标路径是一个已经存在的文件，则覆盖目标文件。-*/
            @copy($src, $dest);
        }else{
            $arr_dest = _utils_io_explode_path($dest);
            array_pop($arr_dest);
            io_mkdir(implode('/', $arr_dest));
            copy($src, $dest);
        }
    }
}

/**
 * 内部函数。
 * 初始化目录、文件列表参数。
 */
function _utils_io_init_fileset($fileset){
    $copy_fileset = array('include' => array(), 'exclude' => array());
    /*- 设置包含的目录、文件列表 -*/
    if(array_key_exists('include', $fileset)){
        for($i = 0; $i < count($fileset['include']); $i++){
            if(array_key_exists('name', $fileset['include'][$i])){
                array_push($copy_fileset['include'], $fileset['include'][$i]);
            }
        }
    }
    /*- 设置排除的目录、文件列表 -*/
    if(array_key_exists('exclude', $fileset)){
        for($i = 0; $i < count($fileset['exclude']); $i++){
            if(array_key_exists('name', $fileset['exclude'][$i])){
                array_push($copy_fileset['exclude'], $fileset['exclude'][$i]);
            }
        }
    }
    return $copy_fileset;
}

/**
 * 将路径切分为数组。
 * @param $path {String} 需要切分的路径。
 */
function _utils_io_explode_path($path){
    $path = str_replace("\\", "/", $path);
    $path = trim(trim($path), '/');
    return explode('/', $path);
}
?>