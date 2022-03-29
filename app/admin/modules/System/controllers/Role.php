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
        $roles               = [];
        if ($list) {
            $total = AdminAuthItemModel::count($count);

            foreach ($list as &$item) {
                $item['create_date'] = date('Y-m-d H:i', $item['created_at']);
                unset($item['created_at']);
                $roles[$item['name']] = $item['description'];
            }
        }

        $this->success([ 'list' => $list, 'total' => intval($total), 'roles' => $roles ]);
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

        if (AdminAuthItemChildModel::update([ 'parent' => $name ], [ 'parent' => $old_name ]) && AdminAuthItemModel::update($data, [ 'name' => $old_name, 'type' => 1 ]))
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

    public function treeAction()
    {
        $name  = $this->getRequest()->getQuery('name');
        $roles = AdminAuthItemChildModel::find('child', [ 'parent' => $name ]);
        if ($roles)
            $roles = array_column($roles, 'child', 'child');

        $list = AdminAuthItemModel::find([ 'name', 'group', 'class', 'description' ], [ 'type' => 0, 'ORDER' => [ "sort" => 'DESC' ] ]);
        $tree = [];

        foreach ($list as $item) {
            if (!isset($tree[$item['group']])) {
                $tree[$item['group']] = [
                    'title' => $item['group'],
                    'value' => false,
                    'child' => []
                ];
            }

            if (!isset($tree[$item['group']]['child'][$item['class']])) {
                $tree[$item['group']]['child'][$item['class']] = [
                    'title' => $item['class'],
                    'value' => false,
                    'child' => []
                ];
            }

            $roles[$item['name']] = isset($roles[$item['name']]);

            $tree[$item['group']]['child'][$item['class']]['child'][$item['description']] = [
                'title' => $item['description'],
                'url'   => $item['name'],
                'value' => $roles[$item['name']]
            ];
        }
        $this->success([ 'tree' => $tree, 'roles' => $roles ]);
    }

    public function setPurviewAction()
    {
        $post = $this->getRequest()->getPost();

        if (!isset($post['name']) || !$post['name'])
            $this->error('参数错误');

        $name = trim($post['name']);

        AdminAuthItemChildModel::delete([ 'parent' => $name ]);

        if (!$post['purviews'])
            $this->success();

        $data = [];
        foreach ($post['purviews'] as $url) {
            $data[] = [ 'parent' => $name, 'child' => $url ];
        }
        AdminAuthItemChildModel::insertMany($data);

        $this->success();
    }

    public function deleteAction()
    {
        $name = $this->getRequest()->getQuery('name');
        $name = trim($name);
        AdminAuthItemChildModel::delete([ 'parent' => $name ]);
        AdminAuthItemModel::delete([ 'name' => $name, 'type' => 1 ]);
        $this->success();
    }
}