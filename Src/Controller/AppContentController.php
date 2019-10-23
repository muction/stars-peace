<?php
namespace Stars\Peace\Controller;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Stars\Peace\Entity\NavMenu;
use Stars\Peace\Service\AppContentService;
use App\Http\Controllers\Controller;
use Stars\Peace\Service\NavMenuService;

/**
 *
 * 站点基类控制器
 *  可以相应页面或json
 * Class AppContentController
 * @package Stars\Peace\Controller
 */
abstract class AppContentController extends Controller
{
    /**
     * 导航菜单
     * @var array
     */
    protected $navMenus = [];

    /**
     * 是否为ajax 请求
     * @var bool
     */
    protected $isAjaxRequest = false;

    /**
     * 激活菜单信息
     * @var null
     */
    protected $activeMenuInfo = null;

    /**
     * 当前激活的导航Id
     * @var null
     */
    protected $activeNavId = null;

    /**
     * 当前激活的路由名称
     * @var null
     */
    protected $activeRouterName = null;

    /**
     * 当前使用的模板名称
     * @var null
     */
    protected $templateName = null;

    /**
     * 分发到模板中的所有变量
     * @var array
     */
    protected $assign = [];

    /**
     * 路径导航
     * @var null
     */
    protected $crumbs = null;

    /**
     * 所有绑定数据输出
     * @var null
     */
    protected $pageData = [];

    /**
     * 地区，语言标识
     * @var null
     */
    protected $locale = null;

    /**
     * appContentService
     * @var null
     */
    protected $appContentService = null;

    /**
     * navMenuService
     * @var null
     */
    protected $navMenuService = null;

    /**
     * inner 参数
     * @var null
     */
    protected $inner = null;

    /**
     * 构造
     * AppContentController constructor.
     */
    public function __construct()
    {
        //行为前钩子
        $this->hookStart();

        //进行中钩子
        $this->initAppData();

        //完成钩子
        $this->hookComplete();
    }

    /**
     * 开始
     */
    public function hookStart(){

    }

    /**
     * 完成
     */
    public function hookComplete(){

    }

    /**
     * 初始化App所需数据
     */
    private function initAppData(){

        $this->appContentService = new AppContentService();
        $this->navMenuService = new NavMenuService();
        $this->locale = checkSiteUrlLangEnv() ;
        $this->activeRouterName = \request()->route()->getName()  ;
        $this->inner = \request()->get('inner') ;

        $this->activeMenuInfo = $this->appContentService->formatActiveMenuInfo( $this->activeRouterName , $this->inner ) ;

        if(! $this->activeMenuInfo){
            throw new \Exception("当前路由未匹配到菜单信息: {$this->activeRouterName}" , 500);
        }

        //加载必须信息
        $this->crumbs = $this->navMenuService->crumbs( $this->activeMenuInfo['id'] );

        if(!$this->isAjaxRequest ){
            //导航数据，缓存有效期见 stars.cache.navMenu
            $cacheNavMenusKey = $this->locale.'_navMenus' ;
            $this->navMenus  = Cache::get( $cacheNavMenusKey ) ;

            if(!$this->navMenus ){
                $peaceNavMenuService = new NavMenuService();
                $this->navMenus = $peaceNavMenuService->articleTree( config('stars.nav.'. App::getLocale() ) );
                Cache::put( $cacheNavMenusKey , $this->navMenus  , config('stars.cache.navMenu' , 0) );
            }

            //获取页面所有数据
            $appContentService = new AppContentService() ;
            $pageData = $appContentService->menuDatas( $this->activeMenuInfo['id'] , (isset($this->activeMenuInfo['inner']) ? $this->activeMenuInfo['inner'] : []) );
            $this->appendPageData( $pageData );
        }

        //输出内容
        $this->assign['activeMenuInfo'] = $this->activeMenuInfo;
        $this->assign['navMenus'] = $this->navMenus;
        $this->assign['crumbs'] = $this->crumbs;

    }


    /**
     * 响应视图|json
     * @param string $template
     * @param array $data
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view( $template ="", $data=[] ){

        if( $this->isAjaxRequest ){
            $appContentService = new AppContentService();
            $bindId=  \request('bindId', 0);
            $bindAlias= \request('bindAlias') ;
            if($bindId && $bindAlias){
                $bindData[str_replace('.','_', $bindAlias )] = $appContentService->findBindPaginateData($bindId , $bindAlias);
                $this->appendPageData( $bindData );
            }
            return $this->responseSuccess( $this->assign );
        }

        if(!$template){
            $this->templateName =
                isset($this->activeMenuInfo['inner']['templateName']) ?
                $this->activeMenuInfo['template_name'] .'.'.$this->activeMenuInfo['inner']['templateName'] :
                $this->activeMenuInfo['template_name'] ;
        }else{
            $this->templateName = $template;
        }

        return view(  $this->templateName , $this->assign  );
    }

    /**
     * 成功响应
     * @param array $data
     * @return array
     */
    public function responseSuccess( $data =[]  ){

        return $this->responseStructure( 200 ,0 , "操作成功" , $data );
    }

    /**
     * 失败响应
     * @param array $data
     * @param int $errorCode
     * @return array
     */
    public function responseError( $data = [] , $errorCode=0 ){
        return $this->responseStructure( 200 ,$errorCode , "操作失败" , $data );

    }

    /**
     * 富文本编辑器上传
     * @param $error
     * @param $url
     * @return array
     */
    public function responseKindUpload( $error, $url ){
        return [
            'error'=> $error ,
            'url' => $url
        ];
    }

    /**
     * 响应API结构体
     * @param $statusCode
     * @param $errorCode
     * @param $msg
     * @param $body
     * @return array
     */
    private function responseStructure( $statusCode, $errorCode , $msg, $body ){
        return [
            'status'=>$statusCode ,
            'error'=> $errorCode,
            'msg' => $msg ,
            'body'=> $body
        ];
    }

    /**
     * 解析内页参数
     * @param $innerString
     * @return array
     */
    private function parseInnerParams( $innerString ){

        if( substr_count( $innerString , '.' ) == config('stars.inner.count') ){
            try{
                $explode = explode( config('stars.inner.delimiter' ,'.')  , $innerString );
                array_map(function( $v){
                    if(!is_numeric($v) || !$v){
                        throw new Exception('空值');
                    }
                }, $explode);

                return ['inner'=> [ 'templateName'=> config('stars.inner.templateName')  , 'bindId'=>$explode[0] , 'infoId'=>$explode[1] ] ];

            }catch (Exception $exception){

            }
        }

        return [];
    }

    /**
     * 合并模板变量
     * @return array
     */
    protected function mergerAssignData(){
        $this->assign['pageData'] = $this->pageData;
        return $this->assign;
    }

    /**
     * 向pageData变量增加数据
     * @param array $data
     * @return array
     */
    protected function appendPageData(array $data ){

        $this->pageData = array_merge( $this->pageData , $data );
        $this->mergerAssignData();
        return $this->pageData;
    }

    /**
     * 标准输出
     *  这里会将您绑定的所有模型数据输出到页面
     * @return mixed
     */
    abstract function page();

    /**
     *  自定义输出
     *   定制化内容页判断，例如详情页
     * @return mixed
     */
    abstract function custom();
}
