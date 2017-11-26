<?php

namespace YiiContrib\Sms\Contracts;

use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Contracts\MessageInterface;
use yii\base\BaseObject;

class Message extends BaseObject implements MessageInterface
{
    /**
     * @var string
     */
    public $type = MessageInterface::TEXT_MESSAGE;
    /**
     * @var array
     */
    public $gateways = [];
    /**
     * @var array
     */
    public $data = [];
    /**
     * @var string
     */
    public $template;
    /**
     * @var string
     */
    public $content;
    
    /**
     * Return the message type.
     *
     * @return string
     */
    public function getMessageType()
    {
        return $this->type;
    }
    
    /**
     * Return message content.
     *
     * @param \Overtrue\EasySms\Contracts\GatewayInterface|null $gateway
     *
     * @return string
     */
    public function getContent(GatewayInterface $gateway = null)
    {
        return $this->content;
    }
    
    /**
     * Return the template id of message.
     *
     * @param \Overtrue\EasySms\Contracts\GatewayInterface|null $gateway
     *
     * @return string
     */
    public function getTemplate(GatewayInterface $gateway = null)
    {
        return $this->template;
    }
    
    /**
     * Return the template data of message.
     *
     * @param \Overtrue\EasySms\Contracts\GatewayInterface|null $gateway
     *
     * @return array
     */
    public function getData(GatewayInterface $gateway = null)
    {
        return $this->data;
    }
    
    /**
     * Return message supported gateways.
     *
     * @return array
     */
    public function getGateways()
    {
        return $this->gateways;
    }
}
