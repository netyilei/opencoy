<?php

use libs\http\ResponseJson;

class BaseController extends \Yaf\Controller_Abstract
{
    public array $user;

    public const HEADER_TOKEN = "X-Token";

    public $headers;

    public $actions = [
        'index/auth/login'  => [ 'GET', 'POST' ],
        'index/index/index' => [ 'GET', 'POST' ],
        'index/index/test'  => [ 'GET', 'POST' ],
        'system/admin/list' => [ 'GET', 'POST' ],
    ];

    public function init()
    {

        $access = strtolower($this->_request->module . '/' . $this->_request->controller . '/' . $this->_request->action);
        $pardon = false;
        if ($this->pardon($access))
            $pardon = true;

        $this->headers = apache_request_headers();

        if (!$pardon && !isset($this->headers[static::HEADER_TOKEN]))
            $this->error('缺少Head参数account-token', 401);

        $this->user = isset($this->headers[static::HEADER_TOKEN]) ? AdminTokenModel::getUser($this->headers[static::HEADER_TOKEN]) : [];


        if (!$pardon) {
            if (!$this->user)
                $this->error('token错误', 401);

            if ($this->user['expires_in'] <= time())
                $this->error('token已过期', 401);

            unset($this->user['expires_in']);

            if (!AdminModel::accessCheck($this->user['id'], $access . ':' . $this->getRequest()->method))
                $this->error('您没有当前功能操作权限');
        }


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

    protected function pardon($access)
    {
        if (isset($this->actions[$access]) && in_array($this->getRequest()->method, $this->actions[$access]))
            return true;
        return false;
    }
}