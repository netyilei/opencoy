<?php

use libs\cdn\Cdn;

class IndexController extends BaseController
{

    public function indexAction()
    {
        echo 'Hello OpenCoy !';
    }

    public function testAction()
    {
        $config = Yaf\Registry::get('config')->cdn->toArray();
        $cdn = new Cdn($config['type'], $config[$config['type']]);

        $re = $cdn->getSign();
        echo "<pre>";

        var_dump($re);
    }

    public function upProfileAction()
    {
        $post = $this->getRequest()->getPost();

        if (!isset($post['name']) || !isset($post['phone']))
            $this->error('请认真填写用户信息');

        $name = trim($post['name']);
        $phone = trim($post['phone']);

        if ($this->user['phone'] == $phone && $this->user['name'] == $name)
            $this->success();

        $re = AdminModel::update(['name' => $name, 'phone' => $phone, 'updated_at' => time()], ['id' => $this->user['id']]);
        if ($re)
            $this->success();

        $this->error();
    }

    public function upPasswordAction()
    {
        $post = $this->getRequest()->getPost();
        if (!isset($post['password']) || !isset($post['old_password']))
            $this->error('请认真填新信息');

        $password = trim($post['password']);
        $old_password = trim($post['old_password']);

        $password_hash = AdminModel::findOne(['id' => $this->user['id']], 'password_hash');

        if (!password_verify($old_password, $password_hash))
            $this->error('旧密码不正确');

        $re = AdminModel::update(['password_hash' => password_hash($password, PASSWORD_DEFAULT), 'updated_at' => time()], ['id' => $this->user['id']]);
        if ($re)
            $this->success();

        $this->error();
    }

    public function upAvatarAction()
    {
        $post = $this->getRequest()->getPost();
        if (!isset($post['avatar']) || !isset($post['avatar']))
            $this->error('参数错误');

        $re = AdminModel::update(['avatar' => $post['avatar'], 'updated_at' => time()], ['id' => $this->user['id']]);
        if ($re)
            $this->success();

        $this->error();
    }
}
