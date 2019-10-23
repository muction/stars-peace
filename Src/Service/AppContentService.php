<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\MenuBind;
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

            $paginateConfig = config( 'stars.paginate');
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
     * @return array|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function findBindPaginateData( $bindId, $bindAlias='' ){

        $bindInfo =$this->bindInfo($bindId);
        if($bindInfo && $bindAlias== $bindInfo['alias_name']){
            $paginateConfig = config( 'stars.paginate');
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
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
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
                $data =  $className::items( $bind['id'], $bind['alias_name'] , $paginate
                );
            }elseif ( $type == 'paginate')
            {
                $data =  $className::paginate( $bind['id'], $bind['alias_name'] , $paginate);
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
            $data = tdClass2Array($data);
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
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
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
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
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
     * @return  |null
     */
    public function bindInfo( $bindId , $bindAlias ='' ){

        return MenuBind::info( $bindId ,$bindAlias );
    }

}