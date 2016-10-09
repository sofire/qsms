<?php
/**
 * Author: Wen Peng
 * Email: imwwp@outlook.com
 * Create: 2016/10/9 下午4:51
 */

namespace Wenpeng\Qsms;

use Exception;
use Wenpeng\Curl;

class Client
{
    private $appID;
    private $appKey;

    public function __construct($appID, $appKey)
    {
        $this->appID = $appID;
        $this->appKey = $appKey;
    }

    public function appID()
    {
        return $this->appID;
    }

    public function appKey()
    {
        return $this->appKey;
    }

    public function post($url, $params)
    {
        $rand = microtime(true);
        $target = "{$url}?sdkappid={$this->appID}&random={$rand}";

        $curl = new Curl();
        $curl->post($params)->url($target);
        if ($curl->error()) {
            throw new Exception($curl->message());
        }

        $data = json_decode($curl->data());
        if ($data === false) {
            throw new Exception('API返回的JSON异常');
        }
        return $data;
    }
}