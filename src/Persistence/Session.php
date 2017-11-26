<?php

namespace YiiContrib\Sms\Persistence;

use yii\di\Instance;

final class Session extends Persistence
{
    /**
     * @var string|\yii\web\Session The web session component name or instance.
     */
    public $session = 'session';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->session = Instance::ensure($this->session, \yii\web\Session::class);
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
        $expire = time() + $expire;
        $this->session->set($key, "{$value}:{$expire}");
        
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function exists($key): bool
    {
        return null !== $this->get($key);
    }
    
    /**
     * @inheritdoc
     */
    public function get($key)
    {
        $data = $this->session->get($key);
        
        if (!$data) {
            return null;
        }
        list($token, $expiry) = explode(':', $data);
        
        if ($expiry > time()) {
            return $token;
        }
        $this->remove($key);
        
        return null;
    }
    
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function ttl($key): int
    {
        $data = $this->session->get($key);
        
        if (!$data) {
            return 0;
        }
        list(, $expiry) = explode(':', $data);
        
        $ttl = $expiry - time();
        
        return $ttl > 0 ? $ttl : 0;
    }
    
    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        $this->session->remove($key);
    }
    
    /**
     * @inheritdoc
     */
    public function buildKey($phone): string
    {
        return '_sms_token';
    }
}
