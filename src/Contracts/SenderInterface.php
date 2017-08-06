<?php

namespace YiiContrib\Sms\Contracts;

use Overtrue\EasySms\Contracts\MessageInterface;

interface SenderInterface
{
    /**
     * @param MessageInterface $message
     *
     * @return mixed
     */
    public function send(MessageInterface $message);
}