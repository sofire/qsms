<?php
/**
 * Author: Wen Peng
 * Email: imwwp@outlook.com
 * Create: 2016/10/9 下午5:36
 */

namespace Wenpeng\Qsms\Request;

use Wenpeng\Qsms\Client;

class Single
{
    private $client;

    private $type = 0;
    private $target = [];
    private $apiUrl = 'https://yun.tim.qq.com/v3/tlssmssvr/sendsms';

    public function __construct(Client $client, $type = 0)
    {
        $this->type = (int) $type;
        $this->client = $client;
    }

    public function target($phone, $nation = 86)
    {
        $this->target = [
            'nationcode' => $nation,
            'phone'      => $phone
        ];
        return $this;
    }

    public function normal($content, $extend = '', $ext = '')
    {
        $sign = $this->sign($this->target['phone']);
        return $this->client->post($this->apiUrl, [
            'type'      => $this->type,
            'sig'       => $sign,
            'msg'       => $content,
            'tel'       => $this->target,
            'extend'    => $extend,
            'ext'       => $ext
        ]);
    }

    public function template($id, $params, $sign = '', $extend = '', $ext = '')
    {
        $sign = $this->sign($this->target['phone']);
        return $this->client->post($this->apiUrl, [
            'type'      => $this->type,
            'sig'       => $sign,
            'tpl_id'    => (int) $id,
            'params'    => $params,
            'sign'      => $sign,
            'tel'       => $this->target,
            'extend'    => $extend,
            'ext'       => $ext
        ]);
    }

    private function sign($phone)
    {
        return md5($this->client->appKey() . $phone);
    }
}