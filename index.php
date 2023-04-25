<?php

use sxqibo\cloudprinter\feieyun\FeieYun;

include './vendor/autoload.php';

$config = [
    // 飞鹅云注册账号
    'user' => '403236160@qq.com',
    // 飞鹅云开发者 UKEY
    'ukey' => 'YqRM2UyHmxdbbEqq',
    // 打印机回调地址
    'callBackUrl' => '',
    // 飞鹅云接口地址
    'ip' => 'api.feieyun.cn',
    // 飞鹅云接口端口
    'port' => '80',
    // 飞鹅云接口路径
    'path' => '/Api/Open/'
];

// 打印机 SN 号
$sn = '960814221';
// 打印内容
$content = '<C>测试打印</C>';
$content .= '<C>山西岐伯科技</C>';
// 打印几联
$times = 1;

$feie = new FeieYun($config);
var_dump(json_decode($feie->printMsg($sn, $content, $times), true));
