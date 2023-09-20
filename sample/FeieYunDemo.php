<?php

use sxqibo\cloudprinter\feieyun\FeieYun;

require __DIR__ . '/../vendor/autoload.php';

class FeieYunDemo
{
    /**
     * @var string[] 飞鹅云配置
     */
    private $config = [
        // 飞鹅云注册账号
        'user' => '',
        // 飞鹅云开发者 UKEY
        'ukey' => '',
        // 打印机回调地址
        'callBackUrl' => '',
    ];

    /**
     * 打印
     *
     * @param $sn
     * @param $content
     * @param string $times
     * @return void
     */
    public function print($sn, $content, string $times = '1')
    {
        $feie = new FeieYun($this->config);
        print $feie->printMsg($sn, $content, $times) . '\n';
    }

    /**
     * 回调验签
     *
     * @param $param
     * @return void
     */
    public function sign($param)
    {
        $feie = new FeieYun($this->config);
        print $feie->printCallBackSign($param) . '\n';
    }

    public function testPrint()
    {
        // 打印机 SN 号
        $sn = '';

        // 打印内容
        $content = '<C>测试打印</C>';
        $content .= '<C>山西岐伯科技</C>';

        // 打印几联
        $times = '1';

        // 打印
        (new Printer())->print($sn, $content, $times);
    }

    public function testSign()
    {
        $param['orderId'] = '816501678_20160919184316_1419533539';
        $param['status'] = '1';
        $param['stime'] = '1625194910';
        $param['sign'] = 'NW1BNm4oTxyyPBdXHPwuI5gjh2onvyHavrSLnrPAGCp4TnoX1IJTwwX+tXFybdi+bo+OM/1FoIeU4H70fPw0m/z/Fz6EYdDpsBbUZFbbUdj9OJrzY/sdnArkynnYoVkLGOwV0DM1WvCn3iqlskD5O1K6POFDc0006xMK+d3/SSNegSUPMuIvuXG6VKGiDN0rO9hOdXFjrp0b1Td14ofPXKibmGXV7XikC2suU45nWmCBC8lKzhazCiInS/tkRAF8WsS2AiACeMvmonyrT/LZWbsfrd9k6M+kATCOz7EjPEd9z+W8N8Rtbur1m3MZdjAshMfduqQEpRU+w7U6R4sxQA==';

        $this->sign($param);
    }

    public function test()
    {
        $this->testPrint();
        $this->testSign();
    }
}

(new Printer())->test();
