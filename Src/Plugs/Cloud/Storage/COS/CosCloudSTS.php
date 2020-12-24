<?php
/**
 * ------------------------------------------------------
 *
 * ------------------------------------------------------
 */

namespace Stars\Peace\Plugs\Cloud\Storage\COS;
use QCloud\COSSTS\Sts;
use Stars\Peace\Contracts\Cloud\Storage\CloudStorageSTSInterface;
use Stars\Peace\Contracts\Cloud\Storage\CloudStorageOptionInterface;

class CosCloudSTS implements CloudStorageSTSInterface
{

    /**
     * 生成临时秘钥
     * @param null $cosSecretId
     * @param null $cosSecretKey
     * @param null $bucket
     * @param null $region
     * @param int $durationSeconds
     * @param string $allowPrefix
     * @param array $allowActions
     * @return array
     * @throws \Exception
     */
    public function makeUploadKey(CloudStorageOptionInterface $cloudStorageOption)
    {
        $config = array(
            'url' => 'https://sts.tencentcloudapi.com/',
            'domain' => 'sts.tencentcloudapi.com',
            'proxy' => '',
            'secretId' => $cloudStorageOption->getOption('COS_SECRET_ID'), // 固定密钥
            'secretKey' =>$cloudStorageOption->getOption('COS_SECRET_KEY'), // 固定密钥
            'bucket' =>$cloudStorageOption->getOption('COS_BUCKET'), // 换成你的 bucket
            'region' =>$cloudStorageOption->getOption('COS_REGION'), // 换成 bucket 所在园区
            'durationSeconds' => intval($cloudStorageOption->getOption('COS_DURATION_SECONDS')), // 密钥有效期
            'allowPrefix' => $cloudStorageOption->getOption('COS_ALLOW_PREFIX'), // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
            'allowActions' => $cloudStorageOption->getOption('COS_ALLOW_ACTION')
        );
        $sts = new Sts();
        return $sts->getTempKeys($config);
    }
}
