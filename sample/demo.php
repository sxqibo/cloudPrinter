<?php
require __DIR__ . '../vendor/autoload.php';

$config = [
    'user'        => '', // 飞鹅云注册账号
    'ukey'        => 'YqRM2UyHmxdbbEqq', // 飞鹅云开发者 UKEY
    'callBackUrl' => '', // 打印机回调地址
];

// 打印机 SN 号
$sn = '960816217';

// 打印内容
$content = '<C>测试打印</C>';
$content .= '<C>山西岐伯科技</C>';

// 打印几联
$times = 1;

$feie = new FeieYun($config);
$feie->printMsg($sn, $content, $times);
