<?php
namespace Stars\Peace\Controller;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use phpDocumentor\Reflection\Types\Parent_;
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
     * 所有绑定数据
     * @var null
     */
    protected $bindData = [];

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

        if(! \app()->runningInConsole() ){
            //行为前钩子
            $this->hookStart();

            //进行中钩子
            $this->initAppData();

            //完成钩子
            $this->hookComplete();
        }


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

        //服务者设定
        $this->activeNavId = config('stars.nav.'. App::getLocale()  ) ;
        $this->appContentService = new AppContentService();
        $this->navMenuService = new NavMenuService();
        $this->locale = checkSiteUrlLangEnv() ;
        $this->activeRouterName = \request()->route()->getName()  ;
        $this->inner = \request()->get('inner') ;
        $this->isAjaxRequest = \request()->ajax();

        $this->activeMenuInfo = $this->appContentService->formatActiveMenuInfo( $this->activeRouterName , $this->inner , $this->activeNavId) ;

        if(! $this->activeMenuInfo){
            throw new \Exception("当前路由未匹配到菜单信息: {$this->activeRouterName}" , 500);
        }

        //加载必须信息
        $this->crumbs = $this->navMenuService->crumbs( $this->activeMenuInfo['id'] );

        //TODO 优化点
        if(!$this->isAjaxRequest ){
            //导航数据，缓存有效期见 stars.cache.navMenu
            $cacheNavMenusKey = $this->locale.'_navMenus' ;
            $this->navMenus  = Cache::get( $cacheNavMenusKey ) ;

            if(!$this->navMenus ){
                $peaceNavMenuService = new NavMenuService();
                $this->navMenus = $peaceNavMenuService->articleTree( $this->activeNavId , 1);
                Cache::put( $cacheNavMenusKey , $this->navMenus  , configApp('stars.cache.navMenu' , 60) );
            }

            //获取页面所有数据
            $appContentService = new AppContentService() ;
            $bindData = $appContentService->menuDatas( $this->activeMenuInfo['id'] , (isset($this->activeMenuInfo['inner']) ? $this->activeMenuInfo['inner'] : []) );
            $this->appendBindData( $bindData );

            $this->assign['baseInfo']=[
                'activeMenuInfo' => $this->activeMenuInfo ,
                'navMenus' => $this->navMenus ,
                'crumbs' => $this->crumbs
            ] ;
        }

    }


    /**
     * 响应视图|json
     * @param string $template
     * @param array $data
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view( $template ="", array $data=[] ){

        $this->templateName = $template;

        //如果设置了SEO处理则使用配置时的SEO配置内容
        if( config('stars.page.seo') ){
            $seoClassService = config('stars.page.seo');
            $pageSeoInstance = new $seoClassService( $this->bindData , $this->activeMenuInfo );
            $this->assign = array_merge($this->assign , [
               'pageSeo'=>[
                   'title'=> $pageSeoInstance->title() ,
                   'keywords'=> $pageSeoInstance->keywords() ,
                   'description'=> $pageSeoInstance->description() ,
               ]
            ]);
        }

        if($data && is_array($data)){
            $this->assign = array_merge($this->assign , $data ) ;
        }

        //TODO 优化点
        if( $this->isAjaxRequest ){
            return $this->ajaxHandle() ;
        }

        if(!$template){
            $this->templateName =
                isset( $this->activeMenuInfo['inner']['templateName'] ) ?
                $this->locale.'.'. $this->activeMenuInfo['template_name'] .'.'.$this->activeMenuInfo['inner']['templateName'] :
                    $this->locale.'.'. $this->activeMenuInfo['template_name'] ;
        }

        return view(  $this->templateName , $this->assign  );
    }

    /**
     * ajax 请求时处理
     * 如果有扩展需求，覆盖此方法即可
     */
    public function ajaxHandle(){

        return $this->ajaxFindBindData() ;
    }

    /**
     * 获取绑定数据
     */
    final protected function ajaxFindBindData(){

        $appContentService = new AppContentService();
        $bindId=  \request('bindId', 0);
        $bindAlias= \request('bindAlias') ;
        if($bindId && $bindAlias){
            $bindData[str_replace('.','_', $bindAlias )] = $appContentService->findBindPaginateData($bindId , $bindAlias);
            $this->appendBindData( $bindData );
        }
        return $this->responseSuccess( $this->assign );
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
     * 合并模板变量
     * @return array
     */
    protected function mergerAssignData(){
        $this->assign['bindData'] = $this->bindData;
        return $this->assign;
    }

    /**
     * 向bindData变量增加数据
     * @param array $data
     * @return array
     */
    protected function appendBindData(array $data ){

        $this->bindData = array_merge( $this->bindData , $data );
        $this->mergerAssignData();
        return $this->bindData;
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
