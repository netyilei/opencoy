<?php

class AdminController extends BaseController
{
    public function listAction()
    {
        $query = $this->getRequest()->getQuery();

        $limit      = $query['limit'];
        $page       = $query['page'];
        $whereArray = [];

        if (isset($query['username']) && $query['username'])
            $whereArray["admin.username[~]"] = trim($query['username']);

        if (isset($query['name']) && $query['name'])
            $whereArray["admin.name[~]"] = trim($query['name']);

        if (isset($query['phone']) && $query['phone'])
            $whereArray["admin.phone[~]"] = trim($query['phone']);

        if (isset($query['status']) && $query['status'] !== '' && $query['status'] !== null)
            $whereArray["admin.status"] = trim($query['status']);

        $current = 0;
        if ($page > 1)
            $current = (--$page) * $limit;

        $count               = $whereArray;
        $whereArray['ORDER'] = [ "admin.id" => 'DESC' ];
        $whereArray['LIMIT'] = [ $current, $limit ];
        $total               = 0;
        $list                = AdminModel::find([ 'id', 'username', 'name', 'phone', 'avatar', 'status', 'created_at', 'updated_at' ], $whereArray);

        if ($list) {
            $total = AdminModel::count($count);

            foreach ($list as &$item) {
                $item['create_date'] = date('Y-m-d H:i', $item['created_at']);
                $item['status']      = intval($item['status']);
                unset($item['created_at']);
            }
        }

        $this->success([ 'list' => $list, 'total' => intval($total) ]);
    }

    public function createAction()
    {
        $post = $this->getRequest()->getPost();

        $t                     = time();
        $data['username']      = trim($post['username']);
        $data['name']          = trim($post['name']);
        $data['phone']         = trim($post['phone']);
        $data['password_hash'] = password_hash($post['password'], PASSWORD_DEFAULT);
        $data['avatar']        = $post['avatar'];
        $data['status']        = intval($post['status']);
        $data['created_at']    = $t;
        $data['updated_at']    = $t;

        $id = AdminModel::insert($data);
        if ($id)
            $this->success();

        $this->error('操作失败，请联系管理员');
    }

    public function updateAction()
    {
        $post = $this->getRequest()->getPost();

        $data = [];
        if (isset($post['password']) && $post['password'])
            $data['password_hash'] = password_hash($post['password'], PASSWORD_DEFAULT);

        $data['name']       = trim($post['name']);
        $data['status']     = intval($post['status']);
        $data['phone']      = trim($post['phone']);
        $data['avatar']     = $post['avatar'];
        $data['updated_at'] = time();

        if (AdminModel::update($data, [ 'id' => $post['id'] ]))
            $this->success();

        $this->error('操作失败，请联系管理员');
    }

    public function infoAction()
    {
        $query = $this->getRequest()->getQuery();
        $user  = AdminModel::findOne([ 'id' => intval($query['id']) ], [ 'id', 'username', 'name', 'phone', 'avatar', 'status' ]);

        if (!$user)
            $this->error('您操作的管理员不存在');

        $user['password'] = '';
        $user['status']   = intval($user['status']);

        $this->success($user);
    }

    public function viewAction()
    {
        $query = $this->getRequest()->getQuery();
        $user  = AdminModel::findOne([ 'id' => intval($query['id']) ], [ 'id', 'username', 'name', 'phone', 'avatar', 'status', 'created_at', 'updated_at' ]);

        $data = [
            'id'          => [ 'label' => 'ID', 'value' => $user['id'] ],
            'username'    => [ 'label' => '用户名', 'value' => $user['username'] ],
            'name'        => [ 'label' => '姓名', 'value' => $user['name'] ],
            'phone'       => [ 'label' => '电话', 'value' => $user['phone'] ],
            'avatar'      => [ 'label' => '头像', 'value' => $user['avatar'] ],
            'status'      => [ 'label' => '状态', 'value' => $user['status'] == 1 ? '正常' : '停用' ],
            'create_date' => [ 'label' => '创建时间', 'value' => date('Y-m-d H:i', $user['created_at']) ],
            'update_date' => [ 'label' => '修改时间', 'value' => date('Y-m-d H:i', $user['updated_at']) ]
        ];

        $this->success($data);
    }

}