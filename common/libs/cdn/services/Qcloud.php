<?php

namespace libs\cdn\services;

use Exception;
use Qcloud\Cos\Client;
use QCloud\COSSTS\Sts;
use libs\cdn\abstracts\Abstracts;
use libs\cdn\interfaces\Interfaces;

class Qcloud extends Abstracts implements Interfaces
{
    public $region;

    public $appId;

    public $secretId;

    public $secretKey;

    public $bucket;

    /** @var Client */
    protected $client;

    public function __construct($config)
    {
        if (!isset($config['region']) || empty($config['region'])) throw new Exception("Qcloud region cannot be blank");
        if (!isset($config['appId']) || empty($config['appId'])) throw new Exception("Qcloud appId cannot be blank");
        if (!isset($config['secretId']) || empty($config['secretId'])) throw new Exception("Qcloud secretId cannot be blank");
        if (!isset($config['secretKey']) || empty($config['secretKey'])) throw new Exception("Qcloud secretKey cannot be blank");
        if (!isset($config['bucket']) || empty($config['bucket'])) throw new Exception("Qcloud bucket cannot be blank");
        if (!isset($config['host']) || empty($config['host'])) throw new Exception("Qcloud host cannot be blank");

        $this->region    = $config['region'];
        $this->appId     = $config['appId'];
        $this->secretId  = $config['secretId'];
        $this->secretKey = $config['secretKey'];
        $this->bucket    = $config['bucket'];
        $this->host      = $config['host'];

        // bucket的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
        if (strstr($this->bucket, $this->appId) === false)
            $this->bucket = $this->bucket . '-' . $this->appId;

        parent::init();

        $this->client = new Client([
            'region'      => $this->region,
            'credentials' => [
                'secretId'  => $this->secretId,
                'secretKey' => $this->secretKey
            ]
        ]);
    }

    public function upload($localFile, $destFile, $type = 'upload')
    {

        try {
            $result = $this->client->upload(
                $bucket = $this->bucket,
                $key = $destFile,
                $body = ($type == 'base64') ? $localFile : fopen($localFile, 'r+')
            );
            return true;
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function multiUpload($localFile, $destFile)
    {
        try {
            $result = $this->client->upload(
                $bucket = $this->bucket,
                $key = $destFile,
                $body = fopen($localFile, 'rb'),
                $options = [
                    "ACL"          => 'private',
                    'CacheControl' => 'private'
                ]);
            return true;
        } catch (\Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function exists($destFile)
    {
        try {
            $result = $this->client->headObject([ 'Bucket' => $this->bucket, 'Key' => $destFile ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($destFile)
    {
        try {
            $result = $this->client->deleteObject([
                    'Bucket' => $this->bucket,
                    'Key'    => $destFile
                ]
            );
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function sign()
    {
        $sts = new Sts();

        $config = [
            'url'             => 'https://sts.tencentcloudapi.com/',
            'domain'          => 'sts.tencentcloudapi.com', // 域名，非必须，默认为 sts.tencentcloudapi.com
            'proxy'           => '',
            'secretId'        => $this->secretId, // 固定密钥,若为明文密钥，请直接以'xxx'形式填入，不要填写到getenv()函数中
            'secretKey'       => $this->secretKey, // 固定密钥,若为明文密钥，请直接以'xxx'形式填入，不要填写到getenv()函数中
            'bucket'          => $this->bucket, // 换成你的 bucket
            'region'          => $this->region, // 换成 bucket 所在园区
            'durationSeconds' => 1800, // 密钥有效期
            'allowPrefix'     => '*', // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
            // 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看 https://cloud.tencent.com/document/product/436/31923
            'allowActions'    => [
                // 简单上传
                'name/cos:PutObject',
                'name/cos:PostObject',
                // 分片上传
                'name/cos:InitiateMultipartUpload',
                'name/cos:ListMultipartUploads',
                'name/cos:ListParts',
                'name/cos:UploadPart',
                'name/cos:CompleteMultipartUpload'
            ]
        ];

        $data = $sts->getTempKeys($config);

        return [
            'cbnType'     => 'Qcloud',
            'host'        => $this->host,
            'expiredTime' => $data['expiredTime'],
            'credentials' => $data['credentials']
        ];
    }

}