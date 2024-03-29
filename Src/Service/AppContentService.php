<?php
namespace Stars\Peace\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Stars\Peace\Entity\MenuBindEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppContentService
{
    /**
     * 菜单所有绑定App的数据
     * @param $menuId
     * @param array $inner
     * @return array
     */
    public function menuDatas( $menuId , $inner=[] ){

        $menuData = [];
        $bindInfos =$this->menuBindApps( $menuId );

        if( $bindInfos ){

            $paginateConfig = configApp( 'stars.paginate');
            $innerBindId = isset( $inner['bindId']) ? $inner['bindId'] : 0;
            $innerInfoId = isset( $inner['infoId']) ? $inner['infoId'] : 0;

            foreach ($bindInfos as $bind){
                $paginate = isset($paginateConfig[$bind['alias_name']]) ? $paginateConfig[$bind['alias_name']] : $paginateConfig['default'] ;
                $menuData[$bind['alias_name']] =$this->findMenuData( $bind , $innerBindId , $innerInfoId , $paginate) ;
            }
        }

        return $menuData ;
    }

    /**
     * 根据绑定ID查询分页信息
     * @param $bindId
     * @param string $bindAlias
     * @return array|Model|Builder|object|null
     */
    public function findBindPaginateData( $bindId, $bindAlias='' ){

        $bindInfo =$this->bindInfo($bindId , $bindAlias);
        if($bindInfo && $bindAlias== $bindInfo['alias_name']){
            $paginateConfig = configApp( 'stars.paginate');
            $paginate = isset($paginateConfig[$bindInfo['alias_name']]) ? $paginateConfig[$bindInfo['alias_name']] : $paginateConfig['default'] ;
            return  $this->findMenuData( $bindInfo , 0 , 0, $paginate ) ;
        }
        return [];
    }

    /**
     *
     * @param $bind
     * @param $innerBindId
     * @param $innerInfoId
     * @param int $paginate
     * @return Model|Builder|object|null
     */
    public function findMenuData( $bind ,$innerBindId=0 ,$innerInfoId=0 , $paginate=15 ){

        $data = null ;
        $type = strstr( $bind['alias_name'] , '.' , true  );
        $className = "App\Entity\\" . str_replace('Sheet','', $bind['sheet_name'] );
        if( class_exists($className) ){
            if( $type == 'single' )
            {
                $data =  $className::last( $bind['id'], $bind['alias_name']);

            }elseif ( $innerBindId && $innerInfoId)
            {
                $data = $className::info( $innerBindId, $innerInfoId  );

            }elseif ($type == 'list')
            {
                $data =  $className::items( $bind['id'], $bind['alias_name'] , $paginate );
            }elseif ( $type == 'paginate')
            {
                $paginate =  $className::paginate( $bind['id'], $bind['alias_name'] , $paginate);

                $data['links'] = $paginate->links( configApp('stars.paginate.template') ) ;
                $data['data'] = $paginate ;
            }

        }else{

            if( $type=='single')
            {
                $data = self::last( $bind['table_name'] , $bind['id'] ) ;

            } elseif ( $innerBindId && $innerInfoId )
            {

            }elseif ($type == 'list')
            {
                $data =  self::items(  $bind['table_name'] , $bind['id'] , $paginate);
            }
        }

        if(!is_array($data)){
            $data = stdClass2Array($data);
        }

        return $data;
    }

    /**
     * 菜单绑定的所有App
     * @param $menuId
     * @return array
     */
    public function menuBindApps( $menuId ){

        $return= DB::table('menu_binds')
            ->where('menu_id', $menuId)
            ->get();

        $return = $return ? array_map( function( $v ){
            return stdClass2Array($v);
        } , $return->toArray() ) : [];

        return $return ;
    }


    /**
     * 获取多个信息
     * @param $tableName
     * @param $bind_id
     * @return Model|Builder|object|null
     */
    public static function items( $tableName, $bind_id , $limit =10 , $select='*' , $orderRaw='`id` DESC'){

        return DB::table( $tableName )
            ->where('bind_id', $bind_id )
            ->selectRaw( $select )
            ->orderByRaw($orderRaw)
            ->limit( $limit )
            ->get();
    }

    /**
     * 详细信息
     * @param $tableName
     * @param $bind_id
     * @param $infoId
     * @return \Illuminate\Support\Collection
     */
    public static function info( $tableName, $bind_id , $infoId ){

        return DB::table( $tableName )
            ->where('bind_id', $bind_id )
            ->find( $infoId );
    }

    /**
     * 获取一个信息
     * @param $tableName
     * @param $bind_id
     * @param string $select
     * @param string $orderRaw
     * @return Model|Builder|object|null
     */
    public static function last( $tableName, $bind_id , $select='*' , $orderRaw='`id` DESC'){

        return DB::table( $tableName )
            ->where('bind_id', $bind_id )
            ->selectRaw( $select )
            ->orderByRaw($orderRaw)
            ->first();
    }

    /**
     * 获取绑定信息
     * @param $bindId
     * @param string $bindAlias
     * @return  null
     */
    public function bindInfo( $bindId , $bindAlias ='' ){
        return MenuBindEntity::where( 'id', $bindId )->where('alias_name', $bindAlias )->first();
    }

    /**
     * 格式化当前激活导航的信息
     * @param $routerName
     * @param string $inner
     * @return array|mixed
     */
    public function formatActiveMenuInfo( $routerName , $inner  , $activeNavId ){

        $navMenuService = new NavMenuService() ;
        $activeMenuInfo = $navMenuService->findByRouterName( $routerName ,$activeNavId );
        if(!$activeMenuInfo){
            return [];
        }

        $activeMenuInfo['template_name'] = $activeMenuInfo['template_name']  &&  $activeMenuInfo['template_name'] ?
            str_replace( [ '/', 'zh.' ,'en.' ,'.blade.php'] , ['.', '' ], $activeMenuInfo['template_name'] )
            : '' ;
        $activeMenuInfo = array_merge ( $activeMenuInfo , parseInnerParams( $inner ) );

        return $activeMenuInfo;
    }
}
