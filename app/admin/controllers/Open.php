<?php

class OpenController extends BaseController
{
    /**
     * 文件上传
     */
    public function uploadAction()
    {

    }

    /**
     * CDN 上传Token
     * @throws Exception
     */
    public function cdnTokenAction()
    {
        $get = $this->getRequest()->getQuery();
        if (!isset($get['path']))
            $this->error('上传路径参数不存在');

        $config = Yaf\Registry::get('config')->cdn->toArray();
        $cdn    = \libs\cdn\Cdn::getInstance($config['type'], $config[$config['type']]);
        $this->success($cdn->getSign());
    }
}