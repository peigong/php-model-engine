<?php
class SinaSAEMemcache{
    private $prefix = "yilab_";
    
    /**
     * 把值按键存入缓存机制。
     * @param String $key 键。
     * @param mixed $var 值。
     * @param Int $expire 过期的秒数。
     * @return Boolean 是否存入成功。
     */
    public function add($key, $val, $expire = 0){
        $result = false;
        try{
            if(function_exists(memcache_init)){
                $key = $this->prifix . $key;
                $memcache = memcache_init();
                $result = memcache_add($memcache, $key, $val, false, $expire);
            }
        }catch(Exception $ex){
        }
        return $result;
    }
    
    /**
     * 把值按键存入缓存机制（如果键存在，覆盖原有的值）。
     * @param String $key 键。
     * @param mixed $var 值。
     * @param Int $expire 过期的秒数。
     * @return Boolean 是否存入成功。
     */
    public function set($key, $var, $expire = 0){
        $result = false;
        try{
            if(function_exists(memcache_init)){
                $key = $this->prifix . $key;
                $memcache = memcache_init();
                $result = memcache_set($memcache, $key, $val, false, $expire);
            }
        }catch(Exception $ex){
        }
        return $result;
    }
    
    /**
     * 根据键取出存入缓存机制的值。
     * @param String $key 键。
     * @return mixed 存入缓存机制的值。
     */
    public function get($key){
        $result = null;
        try{
            if(function_exists(memcache_init)){
                $key = $this->prifix . $key;
                $memcache = memcache_init();
                $result = memcache_get($memcache, $key);
            }
        }catch(Exception $ex){
        }
        return $result;
    }
    
    /**
     * 在缓存机制，把值按键替换掉。
     * @param String $key 需要替换掉的键。
     * @param mixed $var 需要替换掉的值。
     * @return Boolean 是否替换成功。
     */
    public function replace($key, $val){
        $result = false;
        try{
            if(function_exists(memcache_init)){
                $key = $this->prifix . $key;
                $memcache = memcache_init();
                $result = memcache_replace($memcache, $key, $val);
            }
        }catch(Exception $ex){
        }
        return $result;
    }
    
    /**
     * 根据键删除指定的缓存。
     * @param String $key 键。
     * @return Boolean 是否删除成功。
     */
    public function delete($key){
        $result = null;
        try{
            if(function_exists(memcache_init)){
                $key = $this->prifix . $key;
                $memcache = memcache_init();
                $result = memcache_delete($memcache, $key);
            }
        }catch(Exception $ex){
        }
        return $result;
    }
}
?>
