<?php

namespace YiiContrib\Sms\Contracts;

interface TokenInterface
{
    /**
     * @return mixed
     */
    public function generate();
}