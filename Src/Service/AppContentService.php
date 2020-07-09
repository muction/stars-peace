<?php
namespace Stars\Peace\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
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
    public function menuDatas($menuId, $inner=[]){

        $menuData = [];
        $bindInfos = $this->menuBindApps($menuId);
        if( $bindInfos ){
            $paginateConfig = configApp( 'stars.paginate');
            $innerBindId = isset($inner['bindId']) ? $inner['bindId'] : 0;
            $innerInfoId = isset($inner['infoId']) ? $inner['infoId'] : 0;
            foreach ($bindInfos as $bind){
                $paginate = isset($paginateConfig[$bind['alias_name']]) ? $paginateConfig[$bind['alias_name']] : $paginateConfig['default'] ;
                $menuData[$bind['alias_name']] =$this->findMenuData($bind, $innerBindId, $innerInfoId, $paginate) ;
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
    public function findBindPaginateData($bindId, $bindAlias=''){
        $bindInfo =$this->bindInfo($bindId, $bindAlias);
        if($bindInfo && $bindAlias== $bindInfo['alias_name']){
            $paginateConfig = configApp( 'stars.paginate');
            $paginate = isset($paginateConfig[$bindInfo['alias_name']]) ? $paginateConfig[$bindInfo['alias_name']] : $paginateConfig['default'] ;
            return  $this->findMenuData($bindInfo , 0 , 0, $paginate) ;
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
    public function findMenuData($bind ,$innerBindId=0 , $innerInfoId=0 ,$paginate=15){

        $data = null ;
        $type = strstr( $bind['alias_name'] , '.' , true  );
        $className = "App\Entity\\" . str_replace('Sheet','', $bind['sheet_name']);
        $dataClass =  class_exists($className) ? $className : self::class;

        switch ($type){
            //单一，取最后一条
            case 'single' :
                $data =  $dataClass::last($bind['id']);
                break;
            //列表，取所有数据
            case 'list' :
                $data =  $dataClass::items($bind['id'], $paginate);
                break;
            //分页，按分页获取
            case 'paginate' :
                $paginate =  $dataClass::paginate($bind['id'], $bind['alias_name'], $paginate);
                $data['links'] = $paginate->links( configApp('stars.paginate.template') ) ;
                $data['data'] = $paginate ;
                break;
            default:
                if($innerBindId && $innerInfoId){
                    $data = $className::info( $innerBindId, $innerInfoId  );
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
     * @param $bindId
     * @param int $limit
     * @param string $select
     * @param string $orderRaw
     * @return Model|Builder|object|null
     */
    public static function items( $bindId , $limit =10 , $select='*' , $orderRaw='`id` DESC'){
        return DB::table( self::bindSheetTableName( $bindId) )
            ->where('bind_id', $bindId )
            ->selectRaw( $select )
            ->orderByRaw($orderRaw)
            ->limit( $limit )
            ->get();
    }

    /**
     * 详细信息
     * @param $bindId
     * @param $infoId
     * @return \Illuminate\Support\Collection
     */
    public static function info( $bindId , $infoId ){
        return DB::table(self::bindSheetTableName($bindId))
            ->where('bind_id', $bindId )
            ->find( $infoId );
    }

    /**
     * 获取一个信息
     * @param $bindId
     * @param string $select
     * @param string $orderRaw
     * @return Model|Builder|object|null
     */
    public static function last(  $bindId , $select='*' , $orderRaw='`id` DESC'){
        return DB::table(self::bindSheetTableName($bindId))
            ->where('bind_id', $bindId )
            ->selectRaw( $select )
            ->orderByRaw($orderRaw)
            ->first();
    }

    /**
     * 分页
     * @param $bindId
     * @param $aliasName
     * @param $paginate
     * @return LengthAwarePaginator
     */
    public static function paginate( $bindId, $aliasName , $paginate){
        $page = \request('p',1)-1;
        $tableName= self::bindSheetTableName( $bindId) ;
        $total = DB::table($tableName)->count();
        $items = DB::table($tableName)
            ->skip($page* $paginate )
            ->take($paginate)
            ->orderByDesc('id')
            ->get();
        return new LengthAwarePaginator($items , $paginate, $total , $page );
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
     * 查询绑定时表名称
     * @param int $bindId
     * @return mixed
     */
    public static function bindSheetTableName( int $bindId ){
        return MenuBindEntity::where('id',$bindId)->value('table_name');
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
        return array_merge ( $activeMenuInfo , parseInnerParams( $inner ) );
    }
}
