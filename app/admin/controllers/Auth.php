<?php

use libs\http\ResponseJson;
use libs\Util;

class AuthController extends BaseController
{

    public function loginAction()
    {
        $post = $this->getRequest()->getPost();

        if (!isset($post['username']) || !isset($post['password']))
            $this->error('用户名或密码不能是空');

        $post['username']   = trim($post['username']);
        $post['password']   = trim($post['password']);
        $post['login_type'] = $post['login_type'] ? intval($post['login_type']) : 0;

        $admin = AdminModel::findOne([ 'OR' => [ 'username' => $post['username'], 'phone' => $post['username'] ] ]);

        if (!$admin || !password_verify($post['password'], $admin['password_hash']))
            $this->error('用户名或密码不存在');

        $token_data = AdminTokenModel::setToken($admin['id'], $admin['phone'], AdminTokenModel::LOGIN_TYPE_WEB);
        $this->success($token_data, '登录成功');
    }

    public function infoAction()
    {
        $uesr          = $this->user;
        $uesr['roles'] = [ 'admin' ];
        $this->success($uesr);
    }

    public function logoutAction()
    {
        $post = $this->getRequest()->getPost();
        AdminTokenModel::delete(['login_type' =>$post['login_type'],'token' => $post['token']]);
        $this->success([], '操作成功');
    }
}