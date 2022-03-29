<?php


class PurviewController extends BaseController
{

    public function listAction()
    {
        $query = $this->getRequest()->getQuery();
        $limit = $query['limit'];
        $page  = $query['page'];


        $current = 0;
        if ($page > 1)
            $current = (--$page) * $limit;

        $count = [
            'type' => 0
        ];

        $whereArray = [
            'type'  => 0,
            'ORDER' => [ "group", "class" ],
            'LIMIT' => [ $current, $limit ]
        ];
        $list       = AdminAuthItemModel::find([ 'name', 'description', 'group', 'class' ], $whereArray);
        $total      = AdminAuthItemModel::count($count);

        $group = [];
        $class = [];
        foreach ($list as $item) {
            $group[]                 = $item['group'];
            $class[$item['group']][] = $item['class'];
            $class[$item['group']]   = array_unique($class[$item['group']]);
        }
        $group = array_values(array_unique($group));

        $this->success([ 'list' => $list, 'total' => $total, 'group' => $group, 'class' => $class ]);
    }


    public function createAction()
    {
        $post = $this->getRequest()->getPost();

        $t                   = time();
        $data['name']        = strtolower(trim($post['name'])) . ':' . trim($post['method']);
        $data['description'] = trim($post['description']);
        $data['group']       = trim($post['group']);
        $data['class']       = trim($post['class']);
        $data['type']        = 0;
        $data['created_at']  = $t;
        $data['updated_at']  = $t;

        if (AdminAuthItemModel::findOne([ 'name' => $data['name'] ], [ 'name' ]))
            $this->error('路由已存在');

        AdminAuthItemModel::insert($data);
        $this->success();
    }

    public function updateAction()
    {
        $post = $this->getRequest()->getPost();
        $data = [
            'name'        => trim($post['name']) . ":" . trim($post['method']),
            'description' => trim($post['description']),
            'group'       => trim($post['group']),
            'class'       => trim($post['class']),
            'updated_at'  => time()
        ];

        $where = [
            'name' => trim($post['old_name']) . ':' . trim($post['old_method']),
            'type' => 0
        ];

        if (AdminAuthItemModel::update($data, $where))
            $this->success();

        $this->error('操作失败，请联系管理员');
    }

    public function deleteAction()
    {
        $name   = $this->getRequest()->getQuery('name');
        $method = $this->getRequest()->getQuery('method');

        $url = $name . ":" . $method;
        if (AdminAuthItemModel::delete([ 'name' => $url, 'type' => 0 ]))
            $this->success();

        $this->error('操作失败，请联系管理员');
    }

    public function verifyNameAction()
    {
        $name   = $this->getRequest()->getQuery('name');
        $method = $this->getRequest()->getQuery('method');

        if (!$name || !$method)
            $this->success([ 'status' => 0, 'msg' => '请正确填写内容信息' ]);

        $url = trim($name) . ':' . trim($method);
        if (AdminAuthItemModel::findOne([ 'name' => $url, 'type' => 0 ], [ 'name' ]))
            $this->success([ 'status' => 0, 'msg' => '路径与请求方式已存在' ]);

        $this->success([ 'status' => 1 ]);
    }
}