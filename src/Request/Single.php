<?php
/**
 * Author: Wen Peng
 * Email: imwwp@outlook.com
 * Create: 2016/10/9 下午5:36
 */

namespace Sofire\Qsms\Request;

use Sofire\Qsms\Client;

class Single
{
    private $client;

    private $type = 0;
    private $target = [];
    private $apiUrl = 'https://yun.tim.qq.com/v5/tlssmssvr/sendsms';

    public function __construct(Client $client, $type = 0)
    {
        $this->type = (int)$type;
        $this->client = $client;
    }

    public function target($mobile, $nation = '86')
    {
        $this->target = [
            'nationcode' => (string)$nation,
            'mobile'     => (string)$mobile
        ];
        return $this;
    }

    public function normal($content, $extend = '', $ext = '')
    {
        $random = microtime(true);
        $time = time();
        $sig = $this->sig_sha256($this->target['mobile'], $random, $time);

        return $this->client->post($this->apiUrl . "?random=" . $random, [
            'type'   => $this->type,
            'sig'    => $sig,
            'time'   => $time,
            'msg'    => $content,
            'tel'    => $this->target,
            'extend' => $extend,
            'ext'    => $ext
        ]);
    }

    public function template($id, $params, $sign = '', $extend = '', $ext = '')
    {
        $random = $this->random;
        $time = time();
        $sig = $this->sig_sha256($this->target['mobile'], $random, $time);

        return $this->client->post($this->apiUrl . "?random=" . $random, [
            'type'   => $this->type,
            'sig'    => $sig,
            'tpl_id' => (int)$id,
            'params' => $params,
            'sign'   => $sign,
            'time'   => $time,
            'tel'    => $this->target,
            'extend' => $extend,
            'ext'    => $ext
        ]);
    }

    private function sig($mobile)
    {
        return md5($this->client->appKey() . $mobile);
    }

    private function sig_sha256($mobile, $random, $time)
    {
        $str = "appkey=" . $this->client->appKey() . "&random=$random&time=$time&mobile=$mobile";
        return hash('sha256', $str);
    }
}