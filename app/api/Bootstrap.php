<?php

class Bootstrap extends Yaf\Bootstrap_Abstract
{

    public function _initConfig()
    {
        //开启异常捕获
        Yaf\Dispatcher::getInstance()->catchException(TRUE);

        //把配置保存起来
        Yaf\Registry::set('config', Yaf\Application::app()->getConfig());
    }

    public function _initError()
    {
        error_reporting(E_ALL);
    }

    public function _initDatabase()
    {
        $option           = Yaf\Registry::get('config')->db->default->toArray();
        $option['option'] = [ PDO::ATTR_CASE => PDO::CASE_NATURAL ];
        $db               = new \libs\db\Medoo($option);
        Yaf\Registry::set('db', $db);

        $config = Yaf\Registry::get('config')->cache->toArray();
        $cache  = new \libs\cache\Cache($config['type'], $config[$config['type']]);
        Yaf\Registry::set('cache', $cache);

    }

    public function _initView(Yaf\Dispatcher $dispatcher)
    {
        Yaf\Dispatcher::getInstance()->disableView();
        Yaf\Dispatcher::getInstance()->autoRender(FALSE);
    }
}
