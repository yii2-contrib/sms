<?php


namespace YiiContrib\Sms\Persistence;


interface PersistenceInterface
{
    /**
     * @param string $key
     * @param string $value
     * @param int $expire
     *
     * @return mixed
     */
    public function set($key, $value, $expire = 60);
    
    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key);
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function remove($key);
    
    /**
     * @param string $phone
     *
     * @return string
     */
    public function buildKey($phone);
}