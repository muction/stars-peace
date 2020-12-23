<?php

namespace Stars\Peace\Foundation;

use Stars\Peace\Service\CosService;
use Stars\Peace\Service\OssService;
/**
 * 对外提供云存储统一访问方法
 * Class CloudOSService
 * @package Stars\Peace\Foundation
 */
class CloudOSService
{
    /**
     * 支持平台
     * @var string
     */
    private $platform = null;

    /**
     * 当前对象
     * @var null
     */
    private $storage = null;

    /**
     * 配置
     * @var array
     */
    private $options = [];

    /**
     * CloudOSService constructor.
     * @param string $platform 支持：OSS , COS
     */
    public function __construct($platform = 'OSS'){

        $platform = strtoupper($platform);
        $this->platform = ['OSS', 'COS'];
        if(!in_array($platform, $this->platform)){
            throw new \Exception('不支持的云存储平台:'. $platform);
        }

    }

    public function initStorage(array $options){

    }


}
