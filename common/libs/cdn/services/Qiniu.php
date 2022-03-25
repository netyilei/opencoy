<?php

namespace libs\cdn\services;

use libs\cdn\abstracts\Abstracts;
use libs\cdn\interfaces\Interfaces;
use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use Exception;

class Qiniu extends Abstracts implements Interfaces
{
    public $accessKey;

    public $secretKey;

    public $bucket;

    /** @var  BucketManager */
    protected $client;

    protected $lastError = null;

    public function __construct($config)
    {
        if (!isset($config['accessKey']) || empty($config['accessKey'])) throw new Exception("Qiniu accessKey cannot be blank");
        if (!isset($config['secretKey']) || empty($config['secretKey'])) throw new Exception("Qiniu secretKey cannot be blank");
        if (!isset($config['bucket']) || empty($config['bucket'])) throw new Exception("Cdn bucket cannot be blank");
        if (!isset($config['host']) || empty($config['host'])) throw new Exception("Cdn host cannot be blank");

        $this->accessKey = $config['accessKey'];
        $this->secretKey = $config['secretKey'];
        $this->bucket    = $config['bucket'];
        $this->host      = $config['host'];

        parent::init();

        $this->client = $this->getBucketManager();

    }

    public function upload($localFile, $destFile)
    {
        $token     = $this->getAuth()->uploadToken($this->bucket);
        $uploadMgr = new UploadManager();
        [ $ret, $err ] = $uploadMgr->putFile($token, $destFile, $localFile);
        if ($err !== null) {
            $this->lastError = $err;
            return false;
        } else {
            return true;
        }
    }

    public function multiUpload($localFile, $destFile)
    {
        $this->upload($localFile, $destFile);
    }

    public function delete($destFile)
    {
        $err = $this->client->delete($this->bucket, $destFile);
        if ($err) {
            $this->lastError = $err;
            return false;
        }
        return true;
    }

    public function exists($destFile)
    {
        [ $fileInfo, $err ] = $this->client->stat($this->bucket, $destFile);
        if ($err) {
            return false;
        } else {
            return true;
        }
    }

    public function getAuth()
    {
        return new Auth($this->accessKey, $this->secretKey);
    }

    private function getBucketManager()
    {
        return new BucketManager($this->getAuth(), new Config());
    }

    public function sign()
    {
        $token = $this->getAuth()->uploadToken($this->bucket, null, 1800);
        return [
            'cbnType'     => 'Qiniu',
            'host'        => $this->host,
            'expiredTime' => time() + 1800,
            'credentials' => [
                'token' => $token
            ]
        ];
    }
}