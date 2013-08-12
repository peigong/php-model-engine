<?php
require_once(ROOT . "inc/core/cache/cachefactory.class.php");

/**
 * 应用程序上下文对象的文件系统XML格式存储实现。
 */
class FileSystemXmlApplicationContext implements IApplicationContext{	
    private $root = ROOT;
    private $arr_config_path = array();
    private $config_suffix = "conf.xml";
    private $cache = null;
    private $cache_key = "golbal_classes_config";
    private $config = null;
    private $intances = array();
    private static $factory = null;
        
    /**
     * 构造函数。
     */
    function  __construct(){
        // 设置默认配置目录
        $this->setConfigPath(ROOT . 'conf/ioc');
    }
    
    /**
     * 构造函数。
     */
    function FileSystemXmlApplicationContext(){
        $this->__construct();
    }
    /**
     * 设置IOC类配置的目录。
     * @param String $path IOC类配置的目录。
     */
    function setConfigPath($path){
        array_push($this->arr_config_path, $path);
    }
    
    /**
     * 根据配置ID获取类实例。
     * @param String $id 配置ID。
     * @return 类实例。
     */
    public function getBean($id){
        $result = null;
        if(array_key_exists($id, $this->intances)){
            $result = $this->intances[$id];
        }
        if($result == null){
            if($this->config == null){
                $factory = CacheFactory::getIntance();
                $this->cache = $factory->create();
                if($this->cache){
                    $this->config = $this->cache->get($this->cache_key);
                }
                if($this->config == null){
                    $this->config = array();
                    if(count($this->arr_config_path) > 0){
                        foreach($this->arr_config_path as $idx=>$conf_path){
                            $this->initializeConfig($conf_path, $this->config_suffix);
                        }
                    }
                    if($this->cache){
                        $this->cache->add($this->cache_key, $this->config);
                    }
                }
            }
            if(array_key_exists($id, $this->config)){
                $conf = $this->config[$id];
                $clazz = $conf["class"];
                $path = $conf["path"];
                $abstract = $conf["abstract"];
                $parentId = $conf["parent"];
                $properties = $conf["properties"];
                if($abstract && ("true" == $abstract)){
                    //抽象的类配置，不允许实例化。
                    //TODO:抛异常，写日志
                }else{
                    if($parentId 
                        && $this->config 
                        && (strlen($parentId) > 0)){
                        //&& array_key_exists($parentId, $this->config)){
                        $parent = $this->config[$parentId];
                        if($parent 
                            && $parent["properties"] 
                            && (count($parent["properties"]) > 0)){
                            //继承抽象类的属性配置。
                            $properties = array_merge($parent["properties"], $properties);
                        }
                    }
                    $result = $this->create($clazz, $path, $properties);
                    if($result){
                        $this->intances[$id] = $result;
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * 初始化类的配置。
     * @param String $path 配置文件的路径。
     * @param String $suffix 配置文件的后缀。
     */
    private function initializeConfig($path, $suffix){
        if(is_dir($path) && ($dh = opendir($path))){
            while(false !== ($file = readdir($dh))){
                $file_path = $path . "/" . $file;
                if(is_dir($file_path) && ($file != ".") && ($file != "..")){
                    $this->initializeConfig($file_path, $suffix);
                }elseif(is_file($file_path)){
                    $pattern = "/\.$suffix$/i";
                    if(preg_match($pattern, $file) > 0){
                        $config_temp = $this->parse($file_path);
                        $this->config = $this->merge($this->config, $config_temp);
                    }
                }
            }
        }
    }
    
    /**
     * 将XML格式的配置文件解析成数组格式。
     * 
     */
    private function parse($path){
        $temp = array();
		if(file_exists($path)){
			$doc = new DOMDocument();
			$doc->load($path);
			$beans = $doc->getElementsByTagName("bean");
			foreach($beans as $bean){
			    $id = $bean->getAttribute("id");
			    $class = $bean->getAttribute("class");
				$path = $bean->getAttribute("path");
				$parent = $bean->getAttribute("parent");
				if(strlen($id) > 0){
				    $temp[$id] = array(
				        "id" => $id, 
				        "class" => $class, 
				        "path" => $path, 
				        "parent" => $parent, 
				        "properties" => array()
				        );
                    $properties = $bean->getElementsByTagName("property");
                    foreach($properties as $property){
                        $property_name = $property->getAttribute("name");
                        $property_ref = $property->getAttribute("ref");
                        $property_class = $property->getAttribute("class");
                        $property_path = $property->getAttribute("path");
                        if(strlen($property_name) > 0){
                            array_push($temp[$id]["properties"], array(
                                "name" => $property_name, 
                                "ref" => $property_ref,
                                "class" => $property_class, 
                                "path" => $property_path
                            ));
                        }
                    }
				}
			}
		}
		return $temp;
    }
    
    /**
     * 把源数组的内容复制到目标数据。
     * 有冲突时的正确做法是日志报错，当前的实现是直接覆盖。
     * @param Array $des 目标数组。
     * @param Array $src 来源数组。
     * @return 合并后的数据。
     */
    private function merge($des, $src){
        foreach($src as $key=>$val){
            $des[$key] = $val;
        }
        return $des;
    }
    
	/**
	 * 根据配置反射类实例。
	 * @param String $clazz 类名称。
	 * @param String $path 类实现的文件路径。
	 * @param Array $properties 类属性的配置。
	 * @return 反射的类实例。
	 */
	private function create($clazz, $path, $properties = null){
		$o = null;
        if((strlen($clazz) > 0) && (strlen($path) > 0)){
            $path = ROOT . $path;
            if(file_exists($path)){
                try{
                    require_once($path);
                    $o = new $clazz();
                    if($properties && (count($properties) > 0)){
                        for($i = 0; $i < count($properties); $i++){
                            $property = $properties[$i];
                            $property_name = $property["name"];
                            $property_ref = $property["ref"];
                            $property_class = $property["class"];
                            $property_path = $property["path"];
                            if((strlen($property_name) > 0) && (strlen($property_ref) > 0)){
                                $o->$property_name = $this->getBean($property_ref);
                            }elseif((strlen($property_name) > 0) && (strlen($property_class) > 0) && (strlen($property_path) > 0)){
                                $o->$property_name = $this->create($property_class, $property_path);
                            }
                        }
                    }
                }catch(Exception $ex){
                }
            }
        }		
		return $o;
	}
}
?>