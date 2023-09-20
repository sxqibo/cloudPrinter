<?php

use sxqibo\cloudprinter\xpyun\XpYun;

require __DIR__ . '/../vendor/autoload.php';

class XpYunPrinter
{
    private $config = [
        'user' => '',
        'ukey' => '',
        'backurlFlag' => null
    ];

    public function addPrinter()
    {
        $xpYun = new XpYun($this->config);
        $items = [
            ['sn' => '02YU9YZLFC24F48', 'name' => '东悦打印机1号']
        ];
        print $xpYun->printerAddlist($items);
    }

    public function delPrinter()
    {
        $xpYun = new XpYun($this->config);
        $items = ['02YU9YZLFC24F48'];
        print $xpYun->printerDelList($items);
    }

    public function modifyPrinter()
    {
        $xpYun = new XpYun($this->config);

        print $xpYun->printerEdit('02YU9YZLFC24F48', '东悦打印机100号');
    }

    public function statusPrinter()
    {
        $xpYun = new XpYun($this->config);

        print $xpYun->queryPrinterStatus('02YU9YZLFC24F48');
    }

    public function statusPrinters()
    {
        $xpYun = new XpYun($this->config);

        print $xpYun->queryPrintersStatus(['02YU9YZLFC24F48']);
    }

    public function printMsg()
    {
        $xpYun = new XpYun($this->config);
        $data = <<<EOF
不加标签：默认字体大小<BR>
<BR>
L标签：<L>左对齐<BR></L>
<BR>
R标签：<R>右对齐<BR></R>
<BR>
C标签：<C>居中对齐<BR></C>
<BR>
N标签：<N>字体正常大小<BR></N>
<BR>
HB标签：<HB>字体变高一倍<BR></HB>
<BR>
WB标签：<WB>字体变宽一倍<BR></WB>
<BR>
B标签：<B>字体放大一倍<BR></B>
<BR>
HB2标签：<HB2>字体变高二倍<BR></HB2>
<BR>
WB2标签：<WB2>字体变宽二倍<BR></WB2>
<BR>
B2标签：<B2>字体放大二倍<BR></B2>
<BR>
BOLD标签：<BOLD>字体加粗<BR></BOLD>
EOF;
        $sn = '02YU9YZLFC24F48';
        print $xpYun->printMsg($sn, $data);
    }
}

//(new XpYunPrinter())->addPrinter();
//(new XpYunPrinter())->printMsg();
//(new XpYunPrinter())->delPrinter();
//(new XpYunPrinter())->modifyPrinter();
//(new XpYunPrinter())->statusPrinter();
(new XpYunPrinter())->statusPrinters();
