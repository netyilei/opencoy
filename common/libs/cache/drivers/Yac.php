<?php

namespace libs\cache\drivers;

use libs\cache\abstracts\DriverSimple;

/**
 * Yac
 */
class Yac extends DriverSimple {

    /**
     * Yac 对象
     * @var \Yac 
     */
    private $handler;

    /**
     * 构造函数
     * @param array $config 配置
     */
    public function __construct($config = []) {
        // Check yac
        if (!extension_loaded('yac')) {
            throw new \Exception("yac extension is not exists!");
        }
        if (!ini_get('yac.enable')) {
            throw new \Exception("yac is not enabled!");
        }
        $this->init($config);
        $prefix = isset($config['prefix']) ? $config['prefix'] : '';
        $this->handler = new \Yac($prefix);
    }
    
    public function __set($name, $value) {
        return $this->set($name, $value);
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function __unset($name) {
        return $this->del($name);
    }

    /**
     * 检查驱动是否可用
     * @return boolean      是否可用
     */
    public function checkDriver() {
        return true;
    }

    /**
     * 设置键值
     * @param string $key
     * @param string $value
     * @return boolean
     */
    protected function setOne($key, $value) {
        return $this->handler->set($key, $value);
    }

    /**
     * 获取键值
     * @param string $key
     * @return mixed
     */
    protected function getOne($key) {
        return $this->handler->get($key);
    }

    /**
     * 删除键值
     * @param string $key
     * @return boolean
     */
    protected function delOne($key) {
        return $this->handler->delete($key);
    }

}
