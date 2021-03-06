<?php

namespace YiiContrib\Sms\Contracts;

interface TokenInterface
{
    /**
     * @return mixed
     */
    public function generate();
    
    /**
     * @param null|string $key
     *
     * @return bool
     */
    public function remove($key = null);
    
    /**
     * @param null|string $key
     *
     * @return bool
     */
    public function exists($key = null): bool;
    
    /**
     * @param null|string $key
     *
     * @return mixed
     */
    public function get($key = null);
    
    /**
     * @param null|string $key
     *
     * @return int
     */
    public function ttl($key = null): int;
}
