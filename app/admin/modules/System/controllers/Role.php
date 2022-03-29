<?php

class RoleController extends BaseController
{
    public function listAction()
    {
        $query = $this->getRequest()->getQuery();

        $limit      = $query['limit'];
        $page       = $query['page'];
        $whereArray = [
            'type' => 1
        ];

        if (isset($query['name']) && $query['name'])
            $whereArray["name"] = trim($query['name']);

        $current = 0;
        if ($page > 1)
            $current = (--$page) * $limit;

        $count               = $whereArray;
        $whereArray['LIMIT'] = [ $current, $limit ];
        $total               = 0;
        $list                = AdminAuthItemModel::find([ 'name', 'description', 'created_at', 'updated_at' ], $whereArray);

        if ($list) {
            $total = AdminAuthItemModel::count($count);

            foreach ($list as &$item) {
                $item['create_date'] = date('Y-m-d H:i', $item['created_at']);
                unset($item['created_at']);
            }
        }

        $this->success([ 'list' => $list, 'total' => intval($total) ]);
    }

    public function createAction()
    {
        $post = $this->getRequest()->getPost();

        $t                   = time();
        $data['name']        = trim($post['name']);
        $data['description'] = trim($post['description']);
        $data['type']        = 1;
        $data['created_at']  = $t;
        $data['updated_at']  = $t;

        if (AdminAuthItemModel::findOne([ 'name' => $data['name'] ], [ 'name' ]))
            $this->error('角色名已存在');

        AdminAuthItemModel::insert($data);
        $this->success();
    }

    public function updateAction()
    {
        $post = $this->getRequest()->getPost();

        $name        = trim($post['name']);
        $description = trim($post['description']);
        $old_name    = trim($post['old_name']);

        if ($name == $old_name)
            $this->success();

        $data = [
            'name'        => $name,
            'description' => $description,
            'updated_at'  => time()
        ];

        if (AdminAuthItemModel::update($data, [ 'name' => $old_name, 'type' => 1 ]))
            $this->success();

        $this->error('操作失败，请联系管理员');
    }

    public function verifyNameAction()
    {
        $name = $this->getRequest()->getQuery('name');
        if (!$name)
            $this->success([ 'status' => 0, 'msg' => '请正确填写角色名' ]);

        if (AdminAuthItemModel::findOne([ 'name' => trim($name), 'type' => 1 ], [ 'name' ]))
            $this->success([ 'status' => 0, 'msg' => '角色名已存在' ]);

        $this->success([ 'status' => 1 ]);
    }
}