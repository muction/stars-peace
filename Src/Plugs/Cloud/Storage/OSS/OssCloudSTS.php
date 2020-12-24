<?php
/**
 * ------------------------------------------------------
 * 阿里云 - OSS
 * ------------------------------------------------------
 */
namespace Stars\Peace\Plugs\Cloud\Storage\OSS;

use Stars\Peace\Contracts\Cloud\Storage\CloudStorageSTSInterface;
use Stars\Peace\Contracts\Cloud\Storage\CloudStorageOptionInterface;
use AlibabaCloud\Client\AlibabaCloud;
/**
 * @link https://help.aliyun.com/document_detail/100624.html?spm=a2c4g.11186623.2.13.28d06775TEtqyQ#concept-xzh-nzk-2gb
 */
class OssCloudSTS implements CloudStorageSTSInterface
{
    /**
     * 生成秘钥
     * @param CloudStorageOptionInterface $cloudStorageOption
     * @return mixed
     * @throws \AlibabaCloud\Client\Exception\ClientException
     */
    public function makeUploadKey(CloudStorageOptionInterface $cloudStorageOption)
    {
        $query= [
            'RegionId' => $cloudStorageOption->getOption('OSS_REGION'),
            'RoleArn' => $cloudStorageOption->getOption('OSS_ROLE_ARN'),
            'RoleSessionName' =>$cloudStorageOption->getOption('OSS_ROLE_SESSION_NAME'),
        ];
        AlibabaCloud::accessKeyClient(
            $cloudStorageOption->getOption('OSS_ACCESS_KEY_ID'),
            $cloudStorageOption->getOption('OSS_ACCESS_SECRET'))
            ->regionId($cloudStorageOption->getOption('OSS_REGION'))
            ->asDefaultClient();
        $result = AlibabaCloud::rpc()
            ->product('Sts')
            ->scheme('https') // https | http
            ->version('2015-04-01')
            ->action('AssumeRole')
            ->method('POST')
            ->withPolicy(json_encode($cloudStorageOption->getOption('OSS_ALLOW_ACTION')))
            ->host('sts.aliyuncs.com')
            ->options([
                'query' =>$query,
            ])
            ->request();
        return $result->toArray();
    }
}
