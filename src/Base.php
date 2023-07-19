<?php

namespace Ycstar\Weidaijia;

use GuzzleHttp\Client;

class Base
{
    protected string $host;

    protected string $key;

    protected string $source;

    protected array $guzzleOptions = [];

    protected array $options = [];

    public function __construct(string $host, string $key, string $source)
    {
        $this->host = $host;
        $this->key = $key;
        $this->source = $source;
    }

    public function request($method, array $options = [])
    {
        $this->setOptions($options);
        $params = array_merge($this->getOptions(), ['sign' => $this->getSign($this->getOptions())]);
        $response = $this->getHttpClient()->post($this->getUrl($method), [
            'query' => ['source' => $this->source],
            'form_params' => $params,
        ])->getBody()->getContents();
        return json_decode($response, true);
    }

    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    public function getUrl($method): string
    {
        return $this->host.'/api/third/'.$method.'.php';
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getSign(array $options): string
    {
        ksort($options);
        $hashStr = '';
        foreach ($options as $key => $val) {
            if (!$val) {
                continue;
            }
            $hashStr .= $key.$val;
        }
        return strtoupper(MD5($this->key. $hashStr . $this->key));
    }

    /**
     * 获取回调数据
     * @return array
     */
    public function getNotify()
    {
        $data = file_get_contents('php://input');
        return json_decode($data, true);
    }

    /**
     * 获取回调数据回复内容
     * @return array
     */
    public function getNotifySuccessReply()
    {
        return ['error_code'=>0,'error_msg'=>'success'];
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

}