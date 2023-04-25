<?php

namespace sxqibo\cloudprinter;

/**
 * 飞鹅打印机接口
 */
class FeieYun
{
    /**
     * 飞鹅云后台注册账号
     */
    private $user = '';
    /**
     * 飞鹅云后台注册账号后生成的UKEY
     */
    private $ukey = '';
    /**
     * 飞鹅云回调地址
     */
    private $callBackUrl = '';
    /**
     * 接口IP或域名
     */
    private $ip = '';
    /**
     * 接口IP端口
     */
    private $port = 0;
    /**
     * 接口路径
     */
    private $path = '';

    public function __construct($config)
    {
        $this->user = $config['user'];
        $this->ukey = $config['ukey'];
        $this->callBackUrl = isset($config['callBackUrl']) && !empty($config['callBackUrl']) ? $config : '';
        $this->ip = $config['ip'];
        $this->port = $config['port'];
        $this->path = $config['path'];
    }

    /**
     * 批量添加打印机接口 Open_printerAddlist
     *
     * @param string $printerContent 打印机的sn#key
     * @return string 接口返回值
     */
    public function printerAddlist(string $printerContent)
    {
        // 请求时间
        $time = time();

        $msgInfo = [
            'user'           => $this->user,
            'stime'          => $time,
            'sig'            => $this->signature($time),
            'apiname'        => 'Open_printerAddlist',
            'printerContent' => $printerContent
        ];

        $client = new HttpClient($this->ip, $this->port);

        if (!$client->post($this->path, $msgInfo)) {
            return 'error';
        } else {
            return $client->getContent();
        }
    }

    /**
     * 打印订单接口 Open_printMsg
     *
     * @param string $sn 打印机编号sn
     * @param string $content 打印内容
     * @param string $times 打印联数
     * @return string 接口返回值
     */
    public function printMsg(string $sn, string $content, string $times = '1')
    {
        // 请求时间
        $time = time();

        $msgInfo = [
            'user'    => $this->user,
            'stime'   => $time,
            'sig'     => $this->signature($time),
            'apiname' => 'Open_printMsg',
            'sn'      => $sn,
            'content' => $content,
            // 打印次数
            'times'   => $times,
        ];

        if (isset($this->callBackUrl) && !empty($this->callBackUrl)) {
            $msgInfo['backurl'] = $this->callBackUrl;
        }

        $client = new HttpClient($this->ip, $this->port);

        if (!$client->post($this->path, $msgInfo)) {
            return 'error';
        } else {
            // 服务器返回的JSON字符串，建议要当做日志记录起来
            return $client->getContent();
        }
    }

    /**
     * 批量删除打印机 Open_printerDelList
     *
     * @param string $snlist 打印机编号，多台打印机请用减号“-”连接起来
     * @return string         接口返回值
     */
    public function printerDelList(string $snlist)
    {
        // 请求时间
        $time = time();

        $msgInfo = [
            'user'    => $this->user,
            'stime'   => $time,
            'sig'     => $this->signature($time),
            'apiname' => 'Open_printerDelList',
            'snlist'  => $snlist
        ];

        $client = new HttpClient($this->ip, $this->port);

        if (!$client->post($this->path, $msgInfo)) {
            return 'error';
        } else {
            return $client->getContent();
        }
    }

    /**
     * 修改打印机信息接口 Open_printerEdit
     *
     * @param string $sn 打印机编号
     * @param string $name 打印机备注名称
     * @param string|null $phonenum 打印机流量卡号码,可以不传参,但是不能为空字符串
     * @return string 接口返回值
     */
    public function printerEdit(string $sn, string $name, string $phonenum = null): string
    {
        // 请求时间
        $time = time();

        $msgInfo = [
            'user'    => $this->user,
            'stime'   => $time,
            'sig'     => $this->signature($time),
            'apiname' => 'Open_printerEdit',
            'sn'      => $sn,
            'name'    => $name,

        ];

        if ($phonenum != null) {
            $msgInfo['phonenum'] = $phonenum;
        }

        $client = new HttpClient($this->ip, $this->port);

        if (!$client->post($this->path, $msgInfo)) {
            return 'error';
        } else {
            return $client->getContent();
        }
    }

    /**
     * 清空待打印订单接口 Open_delPrinterSqs
     *
     * @param string $sn 打印机编号
     * @return string 接口返回值
     */
    public function delPrinterSqs(string $sn): string
    {
        // 请求时间
        $time = time();

        $msgInfo = [
            'user'    => $this->user,
            'stime'   => $time,
            'sig'     => $this->signature($time),
            'apiname' => 'Open_delPrinterSqs',
            'sn'      => $sn
        ];

        $client = new HttpClient($this->ip, $this->port);

        if (!$client->post($this->path, $msgInfo)) {
            return 'error';
        } else {
            return $client->getContent();
        }
    }

    /**
     * 查询订单是否打印成功接口 Open_queryOrderState
     *
     * @param string $orderid 调用打印机接口成功后,服务器返回的JSON中的编号
     *                          例如：123456789_20190919163739_95385649
     * @return string 接口返回值
     */
    public function queryOrderState(string $orderid): string
    {
        // 请求时间
        $time = time();

        $msgInfo = [
            'user'    => $this->user,
            'stime'   => $time,
            'sig'     => $this->signature($time),
            'apiname' => 'Open_queryOrderState',
            'orderid' => $orderid
        ];

        $client = new HttpClient($this->ip, $this->port);

        if (!$client->post($this->path, $msgInfo)) {
            return 'error';
        } else {
            return $client->getContent();
        }
    }

    /**
     * 查询指定打印机某天的订单统计数接口 Open_queryOrderInfoByDate
     *
     * @param string $sn 打印机的编号
     * @param string $date 查询日期，格式YY-MM-DD，如：2019-09-20
     * @return string 接口返回值
     */
    public function queryOrderInfoByDate(string $sn, string $date): string
    {
        // 请求时间
        $time = time();

        $msgInfo = [
            'user'    => $this->user,
            'stime'   => $time,
            'sig'     => $this->signature($time),
            'apiname' => 'Open_queryOrderInfoByDate',
            'sn'      => $sn,
            'date'    => $date
        ];

        $client = new HttpClient($this->ip, $this->port);

        if (!$client->post($this->path, $msgInfo)) {
            return 'error';
        } else {
            return $client->getContent();
        }
    }

    /**
     * 获取某台打印机状态接口 Open_queryPrinterStatus
     *
     * @param string $sn 打印机编号
     * @return string 接口返回值
     */
    public function queryPrinterStatus(string $sn): string
    {
        // 请求时间
        $time = time();

        $msgInfo = [
            'user'    => $this->user,
            'stime'   => $time,
            'sig'     => $this->signature($time),
            'apiname' => 'Open_queryPrinterStatus',
            'sn'      => $sn
        ];

        $client = new HttpClient($this->ip, $this->port);

        if (!$client->post($this->path, $msgInfo)) {
            return 'error';
        } else {
            return $client->getContent();
        }
    }

    /**
     * 参数签名
     *
     * @param array $param
     * @return bool
     */
    public function printCallBackSign(array $param): bool
    {
        $sign = $param['sign'];
        $signType = "RSA2";
        unset($param['sign']);
        $param = array_filter($param);
        ksort($param);
        $query = http_build_query($param);
        $pubKey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2LDlvyuClqrnKW01FqYg
valPy1/e09ZWlvjb5Nu+0T1PsGhKjF4WBb+D7x3Dy/Db5IHMcpG/Eps6ew6n/6rw
v8Ctu+uZI33YNv9sqAMPjG2EN+WcqCrVrMGUmjITVpIkQEjTdkuismf+VL3x+eJo
W1y/TaLb9vchReBc6IZowRu2yItC+tFbock5Nupsl5uOCKltm3s0VqtiHUrpgVeV
8dVJHLhmLENnLgcTqrkZeKogFDT+fTOhzQPZVEqQgdat/6kcmD44lN4UI7EvVNfe
amwRLgy4e/CpInD9cql+t5eiRLem0+rgPq9RLivM1pRt67crH0WGY1xXtAtzWO0M
MwIDAQAB
-----END PUBLIC KEY-----
';//这里pubKey不能改动，固定公钥
        //转换为openssl格式密钥
        $res = openssl_pkey_get_public($pubKey);
        //调用openssl内置方法验签，返回bool值

        $result = FALSE;
        if ("RSA2" == $signType) {
            $result = (openssl_verify($query, base64_decode($sign), $res, OPENSSL_ALGO_SHA256)===1);
        } else {
            $result = (openssl_verify($query, base64_decode($sign), $res)===1);
        }
        openssl_free_key($res);
        return $result;
    }

    /**
     * signature 生成签名
     *
     * @param string $time 当前UNIX时间戳，10位，精确到秒
     * @return string 接口返回值
     */
    private function signature(string $time): string
    {
        // 公共参数，请求公钥
        return sha1($this->user . $this->ukey . $time);
    }
}
