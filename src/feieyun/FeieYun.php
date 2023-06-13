<?php

namespace sxqibo\cloudprinter\feieyun;

use sxqibo\cloudprinter\tools\HttpClient;

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
    private $ip = 'api.feieyun.cn';
    /**
     * 接口IP端口
     */
    private $port = 80;
    /**
     * 接口路径
     */
    private $path = '/Api/Open/';

    public function __construct($config)
    {
        $this->user = $config['user'];
        $this->ukey = $config['ukey'];
        $this->callBackUrl = isset($config['callBackUrl']) && !empty($config['callBackUrl']) ? $config : '';
    }

    /**
     * 批量添加打印机接口 Open_printerAddlist
     *
     * @param string $printerContent
     *          批量添加规则：
     *          打印机编号SN(必填) # 打印机识别码KEY(必填) # 备注名称(选填) # 流量卡号码(选填)
     *          多台打印机请换行（\n）
     *          每次最多100行(台)。
     * @return string 接口返回值
     */
    public function printerAddlist(string $printerContent)
    {
        $msgInfo = $this->getBaseMsgInfo();

        $msgInfo['apiname'] = 'Open_printerAddlist';
        $msgInfo['printerContent'] = $printerContent;

        return $this->request($msgInfo);
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
        $msgInfo = $this->getBaseMsgInfo();

        $msgInfo['apiname'] = 'Open_printMsg';
        $msgInfo['sn'] = $sn;
        $msgInfo['content'] = $content;
        $msgInfo['times'] = $times;

        if (isset($this->callBackUrl) && !empty($this->callBackUrl)) {
            $msgInfo['backurl'] = $this->callBackUrl;
        }

        return $this->request($msgInfo);
    }

    /**
     * 批量删除打印机 Open_printerDelList
     *
     * @param string $snlist 打印机编号，多台打印机请用减号“-”连接起来
     * @return string         接口返回值
     */
    public function printerDelList(string $snlist)
    {
        $msgInfo = $this->getBaseMsgInfo();

        $msgInfo['apiname'] = 'Open_printerDelList';
        $msgInfo['snlist'] = $snlist;

        return $this->request($msgInfo);
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
        $msgInfo = $this->getBaseMsgInfo();

        $msgInfo['apiname'] = 'Open_printerEdit';
        $msgInfo['sn'] = $sn;
        $msgInfo['name'] = $name;

        if ($phonenum != null) {
            $msgInfo['phonenum'] = $phonenum;
        }

        return $this->request($msgInfo);
    }

    /**
     * 清空待打印订单接口 Open_delPrinterSqs
     *
     * @param string $sn 打印机编号
     * @return string 接口返回值
     */
    public function delPrinterSqs(string $sn): string
    {
        $msgInfo = $this->getBaseMsgInfo();

        $msgInfo['apiname'] = 'Open_delPrinterSqs';
        $msgInfo['sn'] = $sn;

        return $this->request($msgInfo);
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
        $msgInfo = $this->getBaseMsgInfo();

        $msgInfo['apiname'] = 'Open_queryOrderState';
        $msgInfo['orderid'] = $orderid;

        return $this->request($msgInfo);
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
        $msgInfo = $this->getBaseMsgInfo();

        $msgInfo['apiname'] = 'Open_queryOrderInfoByDate';
        $msgInfo['sn'] = $sn;
        $msgInfo['date'] = $date;

        return $this->request($msgInfo);
    }

    /**
     * 获取某台打印机状态接口 Open_queryPrinterStatus
     *
     * @param string $sn 打印机编号
     * @return string 接口返回值
     */
    public function queryPrinterStatus(string $sn): string
    {
        $msgInfo = $this->getBaseMsgInfo();

        $msgInfo['apiname'] = 'Open_queryPrinterStatus';
        $msgInfo['sn'] = $sn;

        return $this->request($msgInfo);
    }

    /**
     * 参数签名
     *
     * @param array $param
     * @return bool
     */
    public static function printCallBackSign(array $param): bool
    {
        $sign = $param['sign'];

        unset($param['sign']);

        $param = array_filter($param);
        ksort($param);
        $query = http_build_query($param);
        // 这里pubKey不能改动，固定公钥
        $pubKey = file_get_contents(__DIR__ . '/signature.txt');
        // 转换为openssl格式密钥
        $res = openssl_pkey_get_public($pubKey);

        // 调用openssl内置方法验签，返回bool值
        $result = (openssl_verify($query, base64_decode($sign), $res, OPENSSL_ALGO_SHA256) === 1);

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

    /**
     * 拼接飞鹅云基本参数
     *
     * @return array
     */
    private function getBaseMsgInfo(): array
    {
        // 请求时间
        $time = time();

        return [
            'user' => $this->user,
            'stime' => $time,
            'sig' => $this->signature($time)
        ];
    }

    /**
     * 请求飞鹅云接口
     *
     * @param $msgInfo
     * @return string
     */
    private function request($msgInfo): string
    {
        $client = new HttpClient($this->ip, $this->port);

        if (!$client->post($this->path, $msgInfo)) {
            return 'error';
        } else {
            return $client->getContent();
        }
    }
}
