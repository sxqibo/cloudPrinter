<?php

namespace sxqibo\cloudprinter\xpyun;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;

/**
 * 芯烨云打印机
 */
final class XpYun
{
    private $user = '';

    private $ukey = '';

    private $backurlFlag = null;

    private $ip = 'https://open.xpyun.net/api/openapi';

    const ADD_URI = '';

    const DEL_URI = '';

    const PRINT_URI = '';

    public function __construct($config)
    {
        $this->user = $config['user'];
        $this->ukey = $config['ukey'];
        $this->backurlFlag = $config['backurlFlag'] ?? null;
    }

    /**
     * 添加打印机
     *
     * @param $items
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function printerAddlist($items)
    {
        $request = $this->getCommonParam();

        $request['items'] = $items;

        return $this->request('/xprinter/addPrinters', $request);
    }

    /**
     * 打印
     *
     * @param string $sn 打印机号
     * @param string $content 打印内容
     * @param string $times 打印联数
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function printMsg(string $sn, string $content, string $times = '1')
    {
        $request = $this->getCommonParam();

        $request['content'] = $content;
        $request['sn'] = $sn;
        $request['copies'] = $times;

        if ($this->backurlFlag != null) {
            $request['backurlFlag'] = $this->backurlFlag;
        }

        return $this->request('/xprinter/print', $request);
    }

    /**
     * 删除打印机
     *
     * @param $items
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function printerDelList($items)
    {
        $request = $this->getCommonParam();

        $request['snlist'] = $items;

        return $this->request('/xprinter/delPrinters', $request);
    }

    /**
     * 修改打印机信息
     *
     * @param string $sn
     * @param string $name
     * @param string|null $cardno
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function printerEdit(string $sn, string $name, string $cardno = null)
    {
        $request = $this->getCommonParam();

        $request['sn'] = $sn;
        $request['name'] = $name;

        if ($cardno != null) {
            $msgInfo['cardno'] = $cardno;
        }

        return $this->request('/xprinter/updPrinter', $request);
    }

    public function queryPrinterStatus(string $sn)
    {
        $request = $this->getCommonParam();

        $request['sn'] = $sn;

        return $this->request('/xprinter/queryPrinterStatus', $request);
    }

    public function queryPrintersStatus($snList)
    {
        $request = $this->getCommonParam();

        $request['snlist'] = $snList;

        return $this->request('/xprinter/queryPrintersStatus', $request);
    }

    /**
     * 清空待打印机订单接口
     */
    public function delPrinterSqs(string $sn)
    {
        $request = $this->getCommonParam();

        $request['sn'] = $sn;

        return $this->request('/xprinter/delPrinterQueue', $request);
    }


    /**
     * 查询订单是否打印成功接口
     */
    public function queryOrderState(string $orderId)
    {
        $request = $this->getCommonParam();

        $request['orderId'] = $orderId;

        return $this->request('/xprinter/queryOrderState', $request);
    }

    /**
     * 查询指定打印机某天的订单统计数接口
     */
    public function queryOrderInfoByDate(string $sn, string $date)
    {
        $request = $this->getCommonParam();

        $request['sn'] = $sn;
        $request['date'] = $date;

        return $this->request('/xprinter/queryOrderStatis', $request);
    }


    /**
     * 发送请求
     *
     * @param $uri
     * @param $data
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request($uri, $data)
    {
        $headers = ['Content-Type' => 'application/json', 'charset' => 'UTF-8'];

        $client = new Client();
        $clientHandler = $client->getConfig('handler');

        $tapMiddleware = Middleware::tap(function ($request) {
            echo json_encode($request->getHeader('Content-Type'));
            echo $request->getBody();
        });

        $response = $client->request(
            'POST',
            $this->ip . $uri,
            [
                'json' => $data,
                'headers' => $headers,
                'handler' => $tapMiddleware($clientHandler)
            ]
        );

        return $response->getBody()->getContents();
    }

    /**
     * 通用参数
     *
     * @return array
     */
    private function getCommonParam()
    {
        $timestamp = time();

        return [
            'user' => $this->user,
            'timestamp' => $timestamp,
            'sign' => sha1($this->user . $this->ukey . $timestamp),
            'debug' => '0'
        ];
    }
}
