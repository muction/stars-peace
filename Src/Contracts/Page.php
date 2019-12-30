<?php


namespace Stars\Peace\Contracts;

/**
 * Interface Page
 * 前台页面Page 接口
 * @package Stars\Peace\Contracts
 */
interface Page
{
    public function __construct( array $bindData , array $activeMenuInfo ) ;
}
