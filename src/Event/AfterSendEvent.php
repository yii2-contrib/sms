<?php

namespace YiiContrib\Sms\Event;

use yii\base\Event;

class AfterSendEvent extends Event
{
    /**
     * @var array
     */
    public $result = [];
}
