<?php


namespace Stars\Peace\Foundation;


use Stars\Peace\Contracts\Page;
use Stars\Peace\Contracts\PageSeo;

abstract class PageSeoFoundation implements Page,PageSeo
{
    /**
     * 页面所有绑定信息
     * @var array|null
     */
    private $bindData = null;

    /**
     * 当前激活的菜单信息
     * @var array|null
     */
    private $activeMenuInfo = null;

    /**
     * 标题
     * @var string
     */
    public $title = "";

    /**
     * 关键字
     * @var string
     */
    public $keywords = "";

    /**
     * @var string 页面介绍
     */
    public $description = "";

    /**
     * 构造时，传入所需数据
     * PageSeoFoundation constructor.
     * @param array $bindData
     * @param array $activeMenuInfo
     */
    public function __construct(array $bindData ,array $activeMenuInfo )
    {
        $this->activeMenuInfo = $activeMenuInfo;
        $this->bindData = $bindData;
        $this->handle( $this->bindData , $this->activeMenuInfo ) ;
    }

    /**
     * 综合处理
     * @return mixed
     */
    abstract function handle( array $bindData =[] ,array $activeMenuInfo=[] );

    /**
     * 页面关键字
     * @return mixed
     */
    final public function keywords(){
        return $this->keywords ;
    }

    /**
     * 页面标签
     * @return mixed
     */
    final public function title(){
        return $this->title ;
    }

    /**
     * 页面简介
     * @return mixed
     */
    final public function description(){
        return $this->description ;
    }

    /**
     * 设置页码title
     * @param $title
     * @return mixed
     */
    final public function setTitle( $title ){
        return $this->title = $title;
    }

    /**
     * 设置页码关键字
     * @param $keyWords
     * @return mixed
     */
    final public function setKeyWords( $keyWords ){
        return $this->keywords = $keyWords ;
    }

    /**
     * 设置页码简介
     * @param $description
     * @return mixed
     */
    final public function setDescription( $description ){
        return $this->description = $description;
    }
}
