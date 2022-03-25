<?php

use libs\http\ResponseJson;

class BaseController extends \Yaf\Controller_Abstract
{
    public array $user;

    public const HEADER_TOKEN = "X-Token";

    public $headers;

    public function init()
    {
        $module     = $this->_request->module. '/';
        $controller = $this->_request->controller . '/';
        $action     = $this->_request->action;

        if (in_array($module . $controller . $action, [ 'Index/Auth/login', 'Index/Index/index', 'Index/Index/test', 'System/Admin/list' ]))
            return true;

        $this->headers = apache_request_headers();
        if (!isset($this->headers[static::HEADER_TOKEN]))
            $this->error('缺少Head参数account-token', 401);

        $user = AdminTokenModel::getUser($this->headers[static::HEADER_TOKEN]);
        if (!$user)
            $this->error('token错误', 401);

        if ($user['expires_in'] <= time())
            $this->error('token已过期', 401);

        unset($user['expires_in']);
        $this->user = $user;

        return true;
    }

    public function success($data = [], $msg = '操作成功', int $code = 200)
    {
        ResponseJson::success($data, $msg, $code);
    }

    public function error(string $msg = '操作失败', int $code = -1, array $data = [])
    {
        ResponseJson::error($msg, $code, $data);
    }
}