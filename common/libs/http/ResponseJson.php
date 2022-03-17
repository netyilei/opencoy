<?php


namespace libs\http;


class ResponseJson
{
    static public function success($data = [], $msg = '操作成功', $code = 200)
    {
        self::error($msg, $code, $data);
    }

    static public function error($msg, $code = '-1', $data = [])
    {
        $d = [
            'msg'  => $msg,
            'code' => $code
        ];

        $d['data'] = $data;

        if ('cli' !== PHP_SAPI) {
            header("content-Type: application/json; charset=utf-8");
            die(json_encode($d, JSON_UNESCAPED_UNICODE));
        } else {
            die(json_encode($d, JSON_UNESCAPED_UNICODE));
        }
    }

    static public function send($res = true)
    {
        if ($res !== false) {
            self::success($res);
        }
        self::error('操作失败');
    }
}