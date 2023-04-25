## 云打印机

### 01、支持的打印机类型
- 飞鹅云小票打印机

### 02、飞鹅小票打印机使用方法

飞鹅官方文档地址：http://help.feieyun.com/document.php

通过 comopser 安装
```
composer require "sxqibo/cloudprinter @dev"
```

支持的方法：
- 批量添加打印机接口：printerAddlist
- 打印订单接口：printMsg
- 批量删除打印机：printerDelList
- 修改打印机信息接口：printerEdit
- 清空待打印订单接口：delPrinterSqs
- 查询订单是否打印成功接口：queryOrderState
- 查询指定打印机某天的订单统计数接口：queryOrderInfoByDate
- 获取某台打印机状态接口：queryPrinterStatus
- 参数签名：printCallBackSign
- 生成签名：signature

这里只给出了打印的方法
```php
$config = [
    // 飞鹅云注册账号
    'user' => '',
    // 飞鹅云开发者 UKEY
    'ukey' => '',
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
$sn = '';
// 打印内容
$content = '<C>测试打印</C>';
$content .= '<C>山西岐伯科技</C>';
// 打印几联
$times = 1;

$feie = new FeieYun($config);
$feie->printMsg($sn, $content, $times);
```

**说明:**
- 在安装目录下的 index.php 中提供了一个测试文件，需填写开发者在飞鹅云平台注册的账号、UKEY 以及 打印机的 SN 号即可进行测试
- 回调地址需要自己编写，本包中没有进行封装
- 设置回调地址需要在飞鹅云的后台中进行配置


