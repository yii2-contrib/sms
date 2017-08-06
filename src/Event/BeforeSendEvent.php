<?php

namespace YiiContrib\Sms\Event;

use yii\base\Event;

class BeforeSendEvent extends Event
{
    /**
     * @var string
     */
    public $to;
    /**
     * @var array|\Overtrue\EasySms\Contracts\MessageInterface
     */
    public $message;
}