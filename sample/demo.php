<?php
require __DIR__ . '/../vendor/autoload.php';

try {
    $config = [
        'user'        => '', // 飞鹅云注册账号
        'ukey'        => '', // 飞鹅云开发者 UKEY
        'callBackUrl' => '', // 打印机回调地址
    ];

    // 打印机 SN 号
    $sn = '';

    // 打印内容
    $content = '<C>测试打印</C>';
    $content .= '<C>山西岐伯科技</C>';

    // 打印几联
    $times = 1;

    $feie = new \sxqibo\cloudprinter\feieyun\FeieYun($config);
    $feie->printMsg($sn, $content, $times);
} catch (\Exception $e) {
    print_r($e->getMessage());
}
