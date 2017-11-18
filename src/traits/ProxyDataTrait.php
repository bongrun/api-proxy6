<?php

namespace traits;

trait ProxyDataTrait
{
    public $ip;
    public $port;
    public $user;
    public $password;

    public function getString()
    {
        return ($this->user && $this->password ? $this->user . ':' . $this->password . '@' : '') . $this->ip . ':' . $this->port;
    }

    public function getStringShort()
    {
        return $this->ip . ':' . $this->port;
    }
}