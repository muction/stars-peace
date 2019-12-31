<?php


namespace Stars\Peace\Contracts;

/**
 * 页面SEO接口
 * Interface PageSeo
 * @package Stars\Peace\Contracts
 */
interface PageSeo
{
    /**
     * title 描述
     * @return mixed
     */
    public function title();

    /**
     * 关键字
     * @return mixed
     */
    public function keywords();

    /**
     * 页面描述
     * @return mixed
     */
    public function description();
}
