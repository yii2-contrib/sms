<?php

namespace YiiContrib\Sms\Component;

use yii\base\Object;
use yii\di\Instance;
use YiiContrib\Sms\Contracts\TokenInterface;
use YiiContrib\Sms\Helpers\TokenHelper;
use YiiContrib\Sms\Persistence\PersistenceInterface;
use YiiContrib\Sms\Persistence\Session;

class Token extends Object implements TokenInterface
{
    /**
     * @var int The token expiry time.
     */
    public $expiry = 60;
    /**
     * @var int The token length
     */
    public $length = 4;
    /**
     * @var \Closure
     */
    public $generator;
    /**
     * @var string|PersistenceInterface
     */
    public $persistence = Session::class;
    /**
     * @var string
     */
    public $seed;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->persistence = Instance::ensure($this->persistence, PersistenceInterface::class);
    }
    
    /**
     * @inheritdoc
     */
    public function generate()
    {
        if ($this->generator && is_callable($this->generator)) {
            $token = call_user_func($this->generator, $this->seed, $this->length, $this->expiry);
        } else {
            $token = TokenHelper::random($this->length);
        }
        
        //persistence
        $this->persistence->set($this->persistence->buildKey($this->seed), $token, $this->expiry);
        
        return $token;
    }
    
    /**
     * @inheritdoc
     */
    public function remove($key = null)
    {
        $this->persistence->remove($key ?: $this->persistence->buildKey($this->seed));
        
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function exists($key = null)
    {
        return $this->persistence->exists($key ?: $this->persistence->buildKey($this->seed));
    }
    
    /**
     * @param null|string $key
     *
     * @return mixed
     */
    public function get($key = null)
    {
        return $this->persistence->get($key ?: $this->persistence->buildKey($this->seed));
    }
}
