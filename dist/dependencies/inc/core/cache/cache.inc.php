<?php
define('CACHE_TYPE_SINASAEMEMCACHE', 'SinaSAEMemcache');

/**
 * 系统缓存实现机制的接口。
 */
interface ICache{
    /**
     * 把值按键存入缓存机制（如果键存在，返回false）。
     * @param String $key 键。
     * @param mixed $var 值。
     * @param Int $expire 过期的秒数。
     * @return Boolean 是否存入成功。
     */
    function add($key, $var, $expire = 0);
    
    /**
     * 把值按键存入缓存机制（如果键存在，覆盖原有的值）。
     * @param String $key 键。
     * @param mixed $var 值。
     * @param Int $expire 过期的秒数。
     * @return Boolean 是否存入成功。
     */
    function set($key, $var, $expire = 0);
    
    /**
     * 根据键取出存入缓存机制的值。
     * @param String $key 键。
     * @return mixed 存入缓存机制的值。
     */
    function get($key);
    
    /**
     * 在缓存机制，把值按键替换掉。
     * @param String $key 需要替换掉的键。
     * @param mixed $var 需要替换掉的值。
     * @return Boolean 是否替换成功。
     */
    function replace($key, $var);
    
    /**
     * 根据键删除指定的缓存。
     * @param String $key 键。
     * @return Boolean 是否删除成功。
     */
    function delete($key);
}
?>
