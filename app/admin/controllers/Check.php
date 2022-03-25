<?php

class CheckController extends BaseController
{
    public function phoneAction()
    {
        $get = $this->getRequest()->getQuery();
        if (!isset($get['phone']))
            $this->success([ 'status' => 0, 'msg' => '请正确填写手机号' ],);

        $phone = trim($get['phone']);

        if (!preg_match("/^((\(\d{2,3}\))|(\d{3}\-))?1(3|5|7|8|9)\d{9}$/", $phone))
            $this->success([ 'status' => 0, 'msg' => '请正确填写手机号' ],);

        $where = [ 'phone' => $phone ];

        if (isset($get['admin_id']) && $get['admin_id'])
            $where['id[!]'] = intval($get['admin_id']);

        $user = AdminModel::findOne($where);
        if ($user)
            $this->success([ 'status' => 0, 'msg' => '手机号已存在' ]);

        $this->success([ 'status' => 1 ]);
    }

    public function usernameAction()
    {
        $get = $this->getRequest()->getQuery();
        if (!isset($get['username']))
            $this->success([ 'status' => 0, 'msg' => '请填写用户账号' ],);

        $username = trim($get['username']);

        if (!preg_match('/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u', $username))
            $this->success([ 'status' => 0, 'msg' => '用户名只能由数字、字母、中文汉字及下划线组成' ],);

        $user = AdminModel::findOne([ 'username' => $username ]);
        if ($user)
            $this->success([ 'status' => 0, 'msg' => '账号已存在' ]);

        $this->success([ 'status' => 1 ]);
    }
}