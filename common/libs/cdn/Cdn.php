<?php

namespace libs\cdn;

use libs\cdn\interfaces\Interfaces;
/**
 * Cdn 类
 * @package libs\cache
 */
class Cdn implements Interfaces
{
    /**
     * 缓存类型
     * @var string
     */
    private $type;

    /**
     * 缓存驱动
     */
    private $service;

    /**
     * 驱动实例集合
     * @var array
     */
    static private $_service = [];

    /**
     * 类实例集合
     * @var array
     */
    static private $_instances = [];

    /**
     * 构造CDN
     * @param string $type  驱动类型
     * @param array $config 驱动配置
     * @throws \Exception   异常
     */
    public function __construct($type, $config) {

        $key = $type . md5(serialize($config));
        if (!isset(self::$_service[$key])) {
            $class = '\\libs\\cdn\\services\\' . ucwords(strtolower($type));
            if (class_exists($class)) {
                self::$_service[$key] = new $class($config);
            } else {
                throw new \Exception("{$class} is not exists!");
            }
        }

        $this->service = self::$_service[$key];
    }

    /**
     * 获取CDN类实例
     * @param $type
     * @param $config
     * @return Cdn|mixed
     * @throws \Exception
     */
    static public function getInstance($type, $config) {
        $key = $type . md5(serialize($config));
        if (!isset(self::$_instances[$key])) {
            self::$_instances[$key] = new Cdn($type, $config);
        }
        return self::$_instances[$key];
    }

    public function upload($localFile, $destFile)
    {
        return $this->service->upload($localFile, $destFile);
    }

    public function multiUpload($localFile, $destFile)
    {
        return $this->service->multiUpload($localFile, $destFile);
    }

    public function exists($destFile)
    {
        return $this->service->exists($destFile);
    }

    public function delete($destFile)
    {
        return $this->service->delete($destFile);
    }

    public function getCdnUrl($destFile)
    {
        return $this->service->getCdnUrl($destFile);
    }

    public function getSign()
    {
        return $this->service->sign();
    }

    public function getLastError()
    {
        return $this->service->getLastError();
    }
}