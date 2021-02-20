<?php
/**
 * ------------------------------------------------------
 * 云存储 - STS授权
 * ------------------------------------------------------
 */
namespace Stars\Peace\Contracts\Cloud\Storage;


interface CloudStorageSTSInterface
{
    public function makeUploadKey(CloudStorageOptionInterface $cloudStorageOption);

}
