<?php
namespace Stars\Peace\Service;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Sts\Sts;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use OSS\OssClient;
use  \Stars\Peace\Contracts\CloudStorage;

/**
 * 阿里云，云存储
 * Class OssService
 * @package App\Http\Service
 */
class OssService implements CloudStorage
{

    /**
     * @inheritDoc
     * @link https://help.aliyun.com/document_detail/100680.html?spm=a2c4g.11186623.2.16.688f3b49t5VMDR#concept-y5r-5rm-2gb
     * @link https://help.aliyun.com/document_detail/28763.html?spm=a2c4g.11186623.2.12.221e39afHAbVLQ#reference-clc-3sv-xdb
     */
    public function getTempUploadKey()
    {
        try{

            $cacheKey = "oss_tmp_upload_oss_ex";
            if($cacheJson = Redis::get($cacheKey)){
               // return json_decode($cacheJson, true);
            }

            $option = [
                'Statement'=>[
                    [
                        'Action'=>[
                           // "oss:*",
//                            "oss:PutObject",
//                            "oss:ListParts",
//                            "oss:AbortMultipartUpload",
                           // "oss:ListObjects"
                            '*'
                        ],
                        'Effect'=>'Allow',
                        'Resource'=>'*'
                    ]
                ],

                'Version'=>"1"
            ];

            AlibabaCloud::accessKeyClient(env('OSS_SECRET_ID'), env('OSS_SECRET_KEY'))
                ->regionId(env('OSS_REGION'))
                ->asDefaultClient();
            $response=  Sts::v20150401()
                ->assumeRole()
                //指定角色ARN
                 ->withRoleArn('acs:ram::1642095751226463:role/anquanhuijiasts')
               // ->withRoleArn('acs:ram::sinochemagri:role/anquanhuijiasts')
                //RoleSessionName即临时身份的会话名称，用于区分不同的临时身份
                ->withRoleSessionName('aliyun_sts_oss')
                //设置权限策略以进一步限制角色的权限
                //以下权限策略表示拥有所有OSS的只读权限
                ->withPolicy(json_encode($option))
                ->connectTimeout(60)
                ->timeout(3000)
                ->request();
            $response= $response->toArray();
            $response= $this->responseStsResult(
                $response['Credentials']['SecurityToken'],
                $response['Credentials']['AccessKeyId'],
                $response['Credentials']['AccessKeySecret'],
                BaseService::nowTime(),
                $response['Credentials']['Expiration']
            );

            Redis::setex($cacheKey,config('hse.timeOut.cos.tmpKey', 1800 ) , json_encode($response) );
            return $response;
        }catch (\Exception $exception){
            Log::info('get_oss_sts_exception', ['msg'=>$exception->getMessage()]);
            throw $exception;
        }
    }

    /**
     *
     * 判断Object 是否存在
     * @param $objectName
     * @return bool
     * @throws \Exception
     */
    public function hasObject($objectName)
    {
        $tmpKey=$this->getTempUploadKey();
        return $this->getOssClient()->doesObjectExist($tmpKey['bucket'] , $objectName);
    }

    /**
     *
     * 获取预览地址
     * @param $objectName
     * @return \OSS\Http\ResponseCore|string
     * @throws \Exception
     */
    public function getPreviewUrl($objectName)
    {
        $tmpKey=$this->getTempUploadKey();
        return $this->getOssClient()->signUrl($tmpKey['bucket'] ,$objectName, env('OSS_DOWNLOAD_DURATION', 3600));
    }

    /**
     * 格式化响应结果
     * @param $sessionToken
     * @param $tmpSecretId
     * @param $tmpSecretKey
     * @param $startTime
     * @param $expiredTime
     * @return array|mixed
     */
    public function responseStsResult($sessionToken, $tmpSecretId, $tmpSecretKey, $startTime, $expiredTime)
    {
        return [
            'credentials'=>[
                'sessionToken'=>$sessionToken,
                'tmpSecretId'=>$tmpSecretId,
                'tmpSecretKey'=>$tmpSecretKey,
            ],
            'startTime'=>$startTime,
            'expiredTime'=>$expiredTime,
            'bucket'=> env('OSS_BUCKET'),
            'region'=> env('OSS_REGION'),
            'endpoint'=>env('OSS_ENDPOINT') ,
            'platform'=> config('hse.upload.platform')
        ];
    }


    /**
     * 获取OSS客户端实例
     * @return OssClient
     * @throws \OSS\Core\OssException
     */
    private function getOssClient(){

        $tmpKey = $this->getTempUploadKey();
        return new OssClient(
            $tmpKey['credentials']['tmpSecretId'],
            $tmpKey['credentials']['tmpSecretKey'],
            $tmpKey['endpoint'],
            false,
            $tmpKey['credentials']['sessionToken']
        );
    }

    public function testUpload(){


        $oss = $this->getOssClient();

        $x=  $oss->uploadFile( env('OSS_BUCKET'), 'web/test.log','/Users/muction/Studio/apps/code/hse-api/storage/logs/laravel-2020-08-27.log' );

        dd($x);
    }
}
