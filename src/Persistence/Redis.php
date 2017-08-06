<?php


namespace YiiContrib\Sms\Persistence;

use yii\redis\Connection;
use yii\di\Instance;

final class Redis extends Persistence
{
    public $redis;
    
    public function init()
    {
        parent::init();
        
        $this->redis = Instance::ensure($this->redis, Connection::class);
    }
    
    /**
     * @param string $key
     * @param string $value
     * @param int $expire
     *
     * @return mixed
     */
    public function set($key, $value, $expire = 60)
    {
        // TODO: Implement set() method.
    }
    
    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        // TODO: Implement exists() method.
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        // TODO: Implement get() method.
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function remove($key)
    {
        // TODO: Implement remove() method.
    }
    
    /**
     * @param string $phone
     *
     * @return string
     */
    public function buildKey($phone)
    {
        // TODO: Implement buildKey() method.
    }
}