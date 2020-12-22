<?php


namespace Stars\Peace\Plugs\Cloud\Storage;

/**
 * ------------------------------------------------------
 *  对象云存储 - 桶
 * ------------------------------------------------------
 */
interface CloudStorageBucketInterface
{
    /**
     * 创建一个Bucket桶
     * @param $bucketName
     * @return mixed
     */
    public function createBucket($bucketName);

}
