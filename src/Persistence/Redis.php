<?php

namespace YiiContrib\Sms\Persistence;

use yii\base\InvalidValueException;
use yii\redis\Connection;
use yii\di\Instance;

final class Redis extends Persistence
{
    /**
     * @var string|array|Connection The component id or configuration array or component object of the redis.
     */
    public $redis = 'redis';
    
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
     * @return bool
     */
    public function set($key, $value, $expire = 60)
    {
        return $this->redis->setex($key, $expire, $value);
    }
    
    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function remove($key)
    {
        return $this->redis->del($key);
    }
    
    /**
     * @param string $phone
     *
     * @return string
     */
    public function buildKey($phone)
    {
        return "sms:token:{$phone}";
    }
}
