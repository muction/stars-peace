<?php

namespace Stars\Peace;

use Illuminate\Support\ServiceProvider;

class StarsPeaceProvider extends ServiceProvider
{
    /**
     * 当前版本号
     */
    const STARS_PEACE_VERSION = '4.2';

    /**
     * 支持主题
     */
    private $supperThemeNames = ['Bamboo'] ;

    /**
     * 使用主题名称
     * @var string
     */
    private $theme = "Bamboo";

    /**
     * 支持命令
     * @var array
     */
    protected $commands = [
        Console\Commands\EntityMake::class,
        Console\Commands\ServiceMake::class,
        Console\Commands\SheetInit::class,
        Console\Commands\SheetMake::class,
        Console\Commands\StarsInit::class,
        Console\Commands\StarsForge::class,
        Console\Commands\StarsVersion::class,
        Console\Commands\AppPack::class,
        Console\Commands\AppUpdate::class,

    ];

    /**
     * 路由中间件
     * @var array
     */
    protected $routeMiddleware = [
        'stars.peace.auth' => Middleware\PeaceMiddleware::class
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        if($this->app->runningInConsole()){
            $this->commands( $this->commands );
        }

        //指定主题
        if(!in_array($this->theme, $this->supperThemeNames)){
            $this->theme = $this->supperThemeNames[0];
        }

        $this->app->shouldSkipMiddleware();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom( __DIR__.'/Config/stars.php' , 'StarsPeace' );
        $this->loadViewsFrom( $this->getViewPath() , 'StarsPeace' );
        $this->loadRoutesFrom( __DIR__. "/Route/route.php" );
        $this->publishes([

            __DIR__ .'/Asset/stars' => public_path( "static/stars/" )  ,
            __DIR__.'/Config/stars.php' => config_path('stars.php'),

        ], 'stars-peace');

        //注册路由中间件
        $this->registryRouteMiddleware();

    }

    /**
     * 注册中间件
     */
    protected function registryRouteMiddleware(){

        foreach ( $this->routeMiddleware as $alias=>$class){
            app('router')->aliasMiddleware( $alias , $class);
        }
    }

    /**
     * 获取视图路径
     * @return string
     */
    private function getViewPath(){
        return __DIR__ .'/Views/'. $this->theme ;
    }
}
