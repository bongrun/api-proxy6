<?php

namespace bongrun\api;

use bongrun\adapter\CurlAdapter;
use bongrun\exception\ProxyApiException;
use bongrun\interfaces\ProxyAccessInstance;
use bongrun\interfaces\ProxyDataInterface;

class Proxy6
{
    const BASE_URL = 'https://proxy6.net/api/';

    /** @var ProxyAccessInstance */
    private $access;
    private $curl;
    private $proxyDataClassName;

    public function __construct(ProxyAccessInstance $access, string $proxyDataClassName, CurlAdapter $curl = null)
    {
        $this->access = $access;
        $this->proxyDataClassName = $proxyDataClassName;
        if ($curl === null) {
            $this->curl = new CurlAdapter(static::BASE_URL);
        } else {
            $this->curl->setBaseUrl(static::BASE_URL);
        }
    }

    public function getBalance()
    {
        $data = $this->getContent('', []);
        return $data['balance'];
    }

    public function getCount()
    {
        $data = $this->getContent('getproxy', []);
        return $data['count'];
    }

    public function getProxy()
    {
        $data = $this->getContent('getcount', ['country' => 'ru']);
        return $data;
    }

    /**
     * @return ProxyDataInterface|array
     */
    public function buy()
    {
        $data = $this->getContent('buy', ['country' => 'ru', 'count' => 1, 'period' => 30, 'version' => 4, 'type' => 'http']);
        $dataList = array_values($data['list'])[0];
        $proxyData = new $this->proxyDataClassName();
        if ($proxyData instanceof ProxyDataInterface) {
            $proxyData->setId($dataList['id']);
            $proxyData->setIp($dataList['ip']);
            $proxyData->setPort($dataList['port']);
            $proxyData->setUser($dataList['user']);
            $proxyData->setPassword($dataList['pass']);
            $proxyData->setType($dataList['type']);
            $proxyData->setDate($dataList['date']);
            $proxyData->setDateEnd($dataList['date_end']);
            $proxyData->setActive($dataList['active']);
            return $proxyData;
        } else {
            return $dataList;
        }
    }

    public function prolong($id)
    {
        $data = $this->getContent('prolong', ['ids' => $id, 'period' => 31]);
        return array_values($data['list'])[0];
    }

    private function getContent($method, $params)
    {
        $this->curl->get($this->access->getKey() . '/' . $method . '/', $params);
        if ($this->curl->getResponseCode() != 200) {
            throw new ProxyApiException('Не получил данные');
        }
        $data = json_decode($this->curl->getResponseBody(), true);
        if ($data['status'] === 'no') {
            throw new ProxyApiException($data['error'], $data['error_id']);
        }
        return $data;
    }
}