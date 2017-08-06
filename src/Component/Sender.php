<?php

namespace YiiContrib\Sms\Component;

use Overtrue\EasySms\Contracts\MessageInterface;
use yii\base\Component;
use yii\di\Instance;
use YiiContrib\Sms\Contracts\SenderInterface;

class Sender extends Component implements SenderInterface
{
    /**
     * @var string|array|Sms The string or config array or the component object of the sms.
     */
    public $sms = 'sms';
    /**
     * @var array The gateways of the sms.
     */
    public $gateways = [];
    /**
     * @var string
     */
    protected $phone;
    
    /**
     * Sender constructor.
     *
     * @param string $phone
     * @param array $config
     */
    public function __construct($phone, array $config = [])
    {
        $this->phone = $phone;
        
        parent::__construct($config);
    }
    
    public function init()
    {
        parent::init();
        
        $this->sms = Instance::ensure($this->sms, Sms::class);
    }
    
    /**
     * @inheritdoc
     */
    public function send(MessageInterface $message)
    {
        return $this->sms->send($this->phone, $message, $this->gateways);
    }
}