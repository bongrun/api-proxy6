<?php

namespace interfaces;

/**
 * Interface ProxyInterface
 * @package interfaces
 */
interface ProxyDataInterface
{
    public function getId():int;

    public function getIp():string;

    public function getPort():int;

    public function getUser():string;

    public function getPassword():string;

    public function getType():string;

    public function getDate();

    public function getDateEnd();

    public function getActive();

    public function setId($id);

    public function setIp($ip);

    public function setPort($port);

    public function setUser($user);

    public function setPassword($password);

    public function setType($type);

    public function setDate($date);

    public function setDateEnd($dateEnd);

    public function setActive($active);

    public function getString():string;

    public function getStringShort():string;
}