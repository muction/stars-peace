<?php
namespace Stars\Peace\Service;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use QCloud\COSSTS\Sts;
use Stars\Peace\Contracts\CloudStorage;
class CosService implements CloudStorage
{
    /**
     * 生成临时上传秘钥
     * 系统会缓存临时上传秘钥
     * 密钥的权限列表。简单上传和分片需要以下的权限，其他权限列表请看
     * @link https://cloud.tencent.com/document/product/436/31923
     * @throws \Exception
     */
    public function getTempUploadKey(){
        try{
            $cacheKey = "cos_tmp_upload_key";
            if($cacheJson = Redis::get($cacheKey)){
                Log::info($cacheKey,['tmp']);
                return json_decode($cacheJson, true);
            }

            $sts = new Sts();
            $config = array(
                'url' => 'https://sts.tencentcloudapi.com/',
                'domain' => 'sts.tencentcloudapi.com',
                'proxy' => '',
                'secretId' => env('COS_SECRET_ID'), // 固定密钥
                'secretKey' => env('COS_SECRET_KEY'), // 固定密钥
                'bucket' => env('COS_BUCKET'), // 换成你的 bucket
                'region' => env('COS_REGION'), // 换成 bucket 所在园区
                'durationSeconds' => intval( env('COS_DURATION_SECONDS')), // 密钥有效期
                'allowPrefix' => '/web/*', // 这里改成允许的路径前缀，可以根据自己网站的用户登录态判断允许上传的具体路径，例子： a.jpg 或者 a/* 或者 * (使用通配符*存在重大安全风险, 请谨慎评估使用)
                'allowActions' => array (
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
                ),
            );

            // 获取临时密钥，计算签名
            $tempKeys = $sts->getTempKeys($config);

//            $tempKeys["bucket"] = $config['bucket'];
//            $tempKeys["region"] = $config['region'];
//            $tempKeys["uploadPath"] = config('hse.upload.cloud_save_prefix_path').date("Y/m/d");

            Log::info($cacheKey,['get']);
            $response= $this->responseStsResult(
                $tempKeys['credentials']['sessionToken'],
                $tempKeys['credentials']['tmpSecretId'],
                $tempKeys['credentials']['tmpSecretKey'],
                $tempKeys['startTime'],
                $tempKeys['expiredTime']
            );
            Redis::setex($cacheKey ,config('hse.timeOut.cos.tmpKey', 1800 ),json_encode($response) );
            return $response;
        }catch (\Exception $exception){
            Log::info('cos_exception',['msg'=>$exception->getMessage()]);
            throw $exception;
        }
    }

    /**
     * 获取临时签名地址
     * @param $key
     * @return string
     */
    public function getPreviewUrl($key){

        try{
            $tempSts = $this->getTempUploadKey();
            $cosClient = new \Qcloud\Cos\Client(
                array(
                    'region' => env('COS_REGION'),
                    'schema' => 'https', //协议头部，默认为 http
                    'credentials'=> array(
                        'secretId'  =>  $tempSts['credentials']['tmpSecretId'],
                        'secretKey' => $tempSts['credentials']['tmpSecretKey'],
                        'token' => $tempSts['credentials']['sessionToken']
                    )
                )
            );

            return $cosClient->getObjectUrl(  env('COS_BUCKET') , $key, '+'.env('COS_DOWNLOAD_DURATION',10).' minutes'); //签名
        }catch (\Exception $exception){
            Log::info('get_preview_url_exception', ['msg'=>$exception->getMessage(),'key'=>$key]);
            return "";
        }
    }

    /**
     * 判断COS对象是否存在
     * @param $objectName
     * @return bool|string
     */
    public function hasObject($objectName){

        try{
            $tempSts = $this->getTempUploadKey();
            $cosClient = new \Qcloud\Cos\Client(
                array(
                    'region' => env('COS_REGION'),
                    'schema' => 'https', //协议头部，默认为 http
                    'credentials'=> array(
                        'secretId'  =>  $tempSts['credentials']['tmpSecretId'],
                        'secretKey' => $tempSts['credentials']['tmpSecretKey'],
                        'token' => $tempSts['credentials']['sessionToken']
                    )
                )
            );
            return $cosClient->doesObjectExist(  env('COS_BUCKET') , $objectName); //签名
        }catch (\Exception $exception){
            Log::info('cos_has_object_exception', ['msg'=>$exception->getMessage(),'key'=>$objectName]);
            return false;
        }
    }

    /**
     * 格式化响应数据
     * @param $sessionToken
     * @param $tmpSecretId
     * @param $tmpSecretKey
     * @param $startTime
     * @param $expiredTime
     * @return array[]|mixed
     */
    public function responseStsResult($sessionToken, $tmpSecretId, $tmpSecretKey, $startTime, $expiredTime )
    {
        return [
            'credentials'=>[
                'sessionToken'=>$sessionToken,
                'tmpSecretId'=>$tmpSecretId,
                'tmpSecretKey'=>$tmpSecretKey,
            ],
            'startTime'=>$startTime,
            'expiredTime'=>$expiredTime,
            'bucket'=> env('COS_BUCKET'),
            'region'=> env('COS_REGION'),
            'uploadPath' => config('hse.upload.cloud_save_prefix_path').date("Y/m/d"),
            'platform'=> config('hse.upload.platform')
        ];
    }
}
