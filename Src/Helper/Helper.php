<?php
/**
 * ********************
 *  自定义函数库文件
 * ********************
 */

/**
 *
 * 根据URl 第一个词组判断网站语言环境例如，且语言必须在配置文件 stars.langs 里配置
 *
 */
if( !function_exists( 'checkSiteUrlLangEnv' ) ){

    function checkSiteUrlLangEnv(){

        $path = request()->path();
        $export =  $path ? explode('/',  $path) : [] ;
        if(isset($export[0]) && in_array($export[0] , array_keys( config('stars.nav' , []) ) ) ){
            return $export[0];
        }
        return app()->getLocale();
    }
}

/**
 * App级别资源路径生成
 */
if( !function_exists( 'assetApp' ) ){

    function assetApp( $path ){

        return asset( env('APP_NAME') .'/'.( app()->getLocale() ).'/'.$path );
    }
}

/**
 * App级别路由生成方法
 */
if( !function_exists( 'routeApp' ) ){
    function routeApp( $routeName , $href = '' , $params=[] ){
        if($href){
            $locale=app()->getLocale();
            return $locale !='zh' ? '/'.app()->getLocale(). $href : $href ;
        }
        return $routeName ? route($routeName, $params ) : '';
    }
}

/**
 * 获取附件内容
 */
if( !function_exists( 'image' ) ){
    function image( $attachment){
        return isset( $attachment['save_file_path'] )
            ? '/storage/'. $attachment['save_file_path'] .'/'.$attachment['save_file_name'] : '';
    }
}

/**
 * 是不是高亮
 */
if( !function_exists('navIsActive') ){
    function navIsActive( $targetRouteName ='' ,$activeValue ='on' ){
        if( $targetRouteName ){
            $nowRouteName= request()->route()->getName();
            $targetRouteName = explode('.', $targetRouteName) ;
            $nowRouteName = explode( '.' , $nowRouteName );
            if($targetRouteName[1] == $nowRouteName[1]){
                return $activeValue;
            }
        }
        return '';
    }
}

/**
 * 是否路由名称相同
 */
if( !function_exists('navIsActiveV2') ){
    function navIsActiveV2( $targetRouteName ='' ,$activeValue ='on' ){
        if( $targetRouteName ){
            $nowRouteName= request()->route()->getName();
            return $targetRouteName === $nowRouteName;
        }
        return '';
    }
}

/**
 * 生产内容参数格式
 */
if( !function_exists( 'makeInnerParams') ){

    function makeInnerParams( $bindId, $infoId ){

        return ['inner'=> implode(  configApp('stars.inner.delimiter'),  func_get_args() ) ];
    }
}


/**
 *
 * 反解析内容参数格式
 */
if( !function_exists( 'parseInnerParams' ) ){

    function parseInnerParams( $innerString ){

        if($innerString && substr_count( $innerString , '.' ) == configApp('stars.inner.count') ){
            try{
                $explode = explode( configApp('stars.inner.delimiter')  , $innerString );
                array_map(function( $v){
                    if(!is_numeric($v) || !$v){
                        throw new Exception('空值');
                    }
                }, $explode);

                return ['inner'=> [ 'templateName'=> configApp('stars.inner.templateName')  , 'bindId'=>$explode[0] , 'infoId'=>$explode[1] ] ];

            }catch (Exception $exception){

            }
        }

        return [];
    }
}

/**
 * 根据时间字符串格式化时间显示
 */
if( !function_exists( 'formatDateTime') ) {

    function formatDateTime($datetime, $format='Y年m月d日' ){

        return date( $format , strtotime($datetime) );
    }
}

/**
 * 对象转数组
 */
if( !function_exists( 'stdClass2Array' ) ){

    function stdClass2Array( $items ){

        return json_decode( json_encode($items) , true ) ;
    }
}

/**
 * 按语言版本获取配置
 */
if( !function_exists( 'configApp' ) ){

    function configApp( $value , $default=null ){

        $newKey = substrAppFront($value ).'.'.app()->getLocale(). substrAppAfter( $value );
        return config( $newKey ,$default );
    }
}

/**
 * 截取到指定位置结束的内容
 *
 * xxx.yy.sss.ggg
 * return xxx
 */
if( !function_exists('substrAppFront') ){

    function substrAppFront( $string , $explode='.' ){
        return substr( $string , 0,strpos( $string , $explode ) );
    }
}

/**
 * 返回结束部分
 * xxx.yy.sss.ggg
 * return .yy.sss.ggg
 *
 */
if( !function_exists('substrAppAfter') ){

    function substrAppAfter( $string , $explode='.' ){

        return stristr( $string , $explode );
    }
}

/**
 * 生成文章管理url
 */
if( !function_exists( "makeArticleUrl" )){
    function makeArticleUrl( $navId , $menuId , $bindId ){
        return route( 'rotate.article.articles',  ['navId'=> $navId , 'menuId'=>$menuId ,'bindId'=>$bindId ] ) ;
    }
}

/**
 * gzcompress 压缩数据
 * @param string $data
 * @return string
 */
function gzcompressBase64(string $data)
{
    return base64_encode(gzcompress($data));
}

/**
 * 解压缩数据
 * @param string $gzcompress
 * @return false|string
 */
function ungzcompressBase64(string $gzcompress)
{
    return gzuncompress(base64_decode($gzcompress));
}
