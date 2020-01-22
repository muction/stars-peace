<?php


namespace Stars\Peace\Contracts;


interface Service
{
    /**
     * 单例
     * @return mixed
     */
    public static function instance();
}