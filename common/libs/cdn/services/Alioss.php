<?php

namespace libs\cdn\services;

use Exception;
use OSS\OssClient;
use libs\cdn\abstracts\Abstracts;
use libs\cdn\interfaces\Interfaces;

class Alioss extends Abstracts implements Interfaces
{

    public $accessKey;

    public $accessSecret;

    public $endPoint;

    public $bucket;

    /** @var  OssClient */
    public $client;

    public function __construct($config)
    {
        if (!isset($config['accessKey']) || empty($config['accessKey'])) throw new Exception("Alioss accessKey cannot be blank");
        if (!isset($config['accessSecret']) || empty($config['accessSecret'])) throw new Exception("Alioss accessSecret cannot be blank");
        if (!isset($config['endPoint']) || empty($config['endPoint'])) throw new Exception("Alioss endPoint cannot be blank");
        if (!isset($config['host']) || empty($config['host'])) throw new Exception("Alioss host cannot be blank");
        if (!isset($config['bucket']) || empty($config['bucket'])) throw new Exception("Alioss bucket cannot be blank");

        $this->accessKey    = $config['accessKey'];
        $this->accessSecret = $config['accessSecret'];
        $this->endPoint     = $config['endPoint'];
        $this->host         = $config['host'];
        $this->bucket       = $config['bucket'];

        parent::init();
        $this->client = new OssClient($this->accessKey, $this->accessSecret, $this->endPoint);
    }

    public function upload($localFile, $destFile)
    {
        $destFile = $this->nomarlizeDestFilePath($destFile);
        try {
            $result = $this->client->uploadFile($this->bucket, $destFile, $localFile);
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function multiUpload($localFile, $destFile)
    {
        $destFile = $this->nomarlizeDestFilePath($destFile);
        try {
            $this->client->multiuploadFile($this->bucket, $destFile, $localFile);
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function exists($destFile)
    {
        if (empty($destFile)) return false;
        $destFile = $this->nomarlizeDestFilePath($destFile);
        return $this->client->doesObjectExist($this->bucket, $destFile);
    }

    public function delete($destFile)
    {
        $destFile = $this->nomarlizeDestFilePath($destFile);
        try {
            $result = $this->client->deleteObject($this->bucket, $destFile);
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    private function nomarlizeDestFilePath($destFile)
    {
        if (strpos($destFile, '/') === 0)
            $destFile = substr($destFile, 1);

        return $destFile;
    }

    public function sign()
    {
        return [];
    }
}