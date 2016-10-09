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
        $this->type = $type;
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

    public function normal($content)
    {
        return $this->client->post($this->apiUrl, [
            'type'  => $this->type,
            'sig'   => $this->signature($this->target['phone']),
            'msg'   => $content,
            'tel'   => $this->target
        ]);
    }

    public function template($id, $params, $sign = '')
    {
        return $this->client->post($this->apiUrl, [
            'type'      => $this->type,
            'sig'       => $this->signature($this->target['phone']),
            'tpl_id'    => $id,
            'params'    => $params,
            'sign'      => $sign,
            'tel'       => $this->target
        ]);
    }

    private function signature($phone)
    {
        return md5($this->client->appKey() . $phone);
    }
}