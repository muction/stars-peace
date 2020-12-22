<?php

namespace Stars\Peace\Plugs\Cloud\Storage\COS;

use QCloud\COSSTS\Sts;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageBucketInterface;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageInterface;
use \Qcloud\Cos\Client;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageObject;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageOptionInterface;

/**
 * ------------------------------------------------------
 *  对象云存储 - 腾讯云
 * ------------------------------------------------------
 */
class CosCloudStorage implements CloudStorageOptionInterface, CloudStorageBucketInterface, CloudStorageObject, CloudStorageInterface
{
    /**
     * 对外单例操作对象
     * @var null
     */
    private static $instance = null;

    /**
     * @var \Qcloud\Cos\Client $cloudInstance
     * @var null
     */
    private $cloudInstance = null;

    /**
     * 设置配置项
     * @var array
     */
    private $option = [];

    private function __construct()
    {
    }

    public function __clone()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 创建bucket，不需要实现
     * @param $bucketName
     * @return mixed|void
     */
    public function createBucket($bucketName)
    {
        return null;
    }

    /**
     * 单文件本地上传
     * @param $localFile
     * @param $saveFile
     * @param $bucket
     * @return mixed|void
     */
    public function simpleUpload($localFile, $saveFile, $bucket)
    {
        return $this->cloudInstance->putObject([
            'bucket'=>$bucket,
            'key'=>$saveFile,
            'Body'=>fopen($localFile, 'r')
        ]);
    }

    /**
     * TODO 分片上传
     * @param $localFile
     * @param $saveFile
     * @param $bucket
     * @param float $size
     * @return mixed|void
     */
    public function sliceUpload($localFile, $saveFile, $bucket, $size = 0.5)
    {
        // TODO: Implement sliceUpload() method.
    }

    /**
     * TODO 危险操作，暂不实现
     * @param $object
     * @param $bucket
     * @return mixed|void
     */
    public function removeObject($object, $bucket)
    {
        return null;
    }

    /**
     * 判断是否有某个对象
     * @param $object
     * @param $bucket
     * @return mixed|void
     */
    public function hasObject($object, $bucket)
    {
        return $this->cloudInstance->doesObjectExist($bucket, $object);
    }

    /**
     * 增加一个配置项
     * @param $key
     * @param $value
     * @return $this|mixed
     */
    public function addOption($key, $value)
    {
        $this->option[$key] = $value;
        return $this;
    }

    /**
     * 获取一个配置项，无时返回null
     * @param $key
     * @return mixed|null
     */
    public function getOption($key)
    {
        return isset($this->option[$key]) ? $this->option[$key] : null;
    }

    /**
     * STS 临时秘钥生成
     * @link https://cloud.tencent.com/document/product/436/31923
     */
    public function makeUploadKey($allowPrefix = "", array $allowActions = [])
    {
        $sts = new Sts();
        $config = array(
            'url' => 'https://sts.tencentcloudapi.com/',
            'domain' => 'sts.tencentcloudapi.com',
            'proxy' => '',
            'secretId' => $this->getOption('COS_SECRET_ID'), // 固定密钥
            'secretKey' => $this->getOption('COS_SECRET_KEY'), // 固定密钥
            'bucket' => $this->getOption('COS_BUCKET'), // 换成你的 bucket
            'region' => $this->getOption('COS_REGION'), // 换成 bucket 所在园区
            'durationSeconds' => intval($this->getOption('COS_DURATION_SECONDS')), // 密钥有效期
            'allowPrefix' => $this->getOption('COS_ALLOW_PREFIX'), // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
            'allowActions' => $allowActions && is_array($allowActions) ? $allowActions : $this->defaultAllowAction()
        );
        return $sts->getTempKeys($config);
    }

    /**
     * 操作对象
     * @param $cosOrgion
     * @param $tempSecretId
     * @param $tempSecretKey
     * @param $sessionToken
     * @return mixed|void
     */
    public function cloudInstance()
    {
        $stsGetTempKeys = $this->getOption('COS_STS_GET_TEMP_KEYS');
        $this->cloudInstance = new Client(
            [
                'region' => $stsGetTempKeys['region'],
                'schema' => 'https', //协议头部，默认为 http
                'credentials' => [
                    'secretId' => $stsGetTempKeys['credentials']['tmpSecretId'],
                    'secretKey' => $stsGetTempKeys['credentials']['tmpSecretKey'],
                    'token' => $stsGetTempKeys['credentials']['sessionToken']
                ]
            ]
        );
        return $this;
    }

    /**
     * 签名一个object
     * @param $bucket
     * @param $object
     * @param $expiredTime
     * @return mixed|void
     */
    public function signObject($bucket, $object, $expiredTime)
    {
        return $this->cloudInstance->getObjectUrl($bucket, $object, '+'.$expiredTime.' minutes');
    }

    /**
     * 默认允许操作
     * @return string[]
     */
    private function defaultAllowAction()
    {
        return [
            // 简单上传
            'name/cos:PutObject',
            'name/cos:PostObject',
            // 分片上传
            'name/cos:InitiateMultipartUpload',
            'name/cos:ListMultipartUploads',
            'name/cos:ListParts',
            'name/cos:UploadPart',
            'name/cos:CompleteMultipartUpload',

            //下载对象
            "name/cos:GetObject",
            //获取HeadObject
            "name/cos:HeadObject",
        ];
    }
}
