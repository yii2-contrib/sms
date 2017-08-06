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
    public function exists($key)
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
            return $data;
        }
        $this->remove($key);
        
        return null;
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
    public function buildKey($phone)
    {
        return '_sms_token';
    }
    
    
}