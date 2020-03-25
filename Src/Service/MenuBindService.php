<?php
namespace Stars\Peace\Service;

use Illuminate\Support\Facades\DB;
use Stars\Peace\Entity\MenuBindEntity;
use Stars\Peace\Entity\NavMenuEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;

class MenuBindService extends ServiceService
{

    /**
     * @param $menuId
     * @return mixed
     */
    public function bindAllInfo( $menuId  ){
        $result= MenuBindEntity::bindAllInfo( $menuId );
        return $result ? $result->toArray() : [];
    }

    /**
     * navmenubind
     * @param $navId
     * @param $menuId
     * @param Request $request
     * @param $bindId
     * @return bool|mixed
     */
    public function navMenuBindSheet($navId, $menuId , Request $request, $bindId){
        $saveAll = [];
        $all = $request->all();

        foreach ($all['sheets'] as $sheetAliasName)
        {
            $bindName = $all['bind-name-'.$sheetAliasName ];
            if(!$bindName) continue;

            $aliasName = $all['bind-alias-'.$sheetAliasName ];

            $options = [
                'column_list' => $all['column-list-'. $sheetAliasName ] ,
                'column_search' => $all['column-search-'. $sheetAliasName ] ,
                'column_required' => $all['column-required-'. $sheetAliasName ] ,
            ];

            $tmp = [
                'menu_id'=>$menuId ,
                'title'=> $bindName  ,
                'sheet_name'=>$sheetAliasName ,
                'alias_name'=>$aliasName ,
                'table_name' => $all['sheets_table_name-'.$sheetAliasName ] ,
                'options'=> json_encode($options) ,
                'order'=>10 ,
                'status'=>1 ,
                'created_at'=>date('Y-m-d H:i:s') ,
                'updated_at'=>date('Y-m-d H:i:s')
            ];

            if($bindId){
                unset( $tmp['menu_id'] , $tmp['created_at'] , $tmp['status'] , $tmp['order'] ) ;
            }
            $saveAll[] = $tmp;
        }

        if($bindId){
            $bindInfo = MenuBindEntity::info( $menuId, $bindId );
            return $bindInfo ? $bindInfo->update( $saveAll[0] ) : false;
        }

        return $saveAll ? MenuBindEntity::saveAll( $saveAll ) : false;
    }

    /**
     * 删除绑定
     * @param $menuId
     * @param $bindId
     * @return mixed
     */
    public function navMenuBindRemove( $menuId ,$bindId ){
        return MenuBindEntity::remove( $menuId, $bindId );
    }

    /**
     * @param $menuId
     * @param $bindId
     * @return mixed
     */
    public function bindInfo( $menuId, $bindId ){
        return MenuBindEntity::info( $menuId, $bindId );
    }

    /**
     * @param $menuId
     * @param $bindId
     * @return array
     */
    public function bindSheetInfo( $menuId, $bindId ){
        $info = self::bindInfo( $menuId, $bindId  ) ;
        if(!$info)  {
            return [];
        }
        $info = $info->toArray();
        $info['sheet']  = [];
        $info['options'] = json_decode( $info['options'] , true );
        $sheet = new SheetService();
        $sheet = $sheet->info( $info['sheet_name'] );
        if($sheet){
            $sheet->setBindInfo( $info ) ;
            $sheet->detail() ;
            $info['sheet']  = $sheet ;
        }

        return $info ;
    }

    /**
     * @param $sourceNavId
     * @param $targetNavId
     * @return bool
     */
    public function bindCopy( $sourceNavId, $targetNavId ){

       try{
           //新老Id映射关系
           $tempOldNewIdMap = [];

           //取出数据源
           $sourceNavMenus = NavMenuEntity::menus($sourceNavId );
           $sourceNavMenus = $sourceNavMenus ? $sourceNavMenus->toArray() : [];
           //开启事务
           DB::beginTransaction();

           //清理原来旧导航菜单数据
           NavMenuEntity::clearNavMenus( $targetNavId );

           foreach ($sourceNavMenus as $menu){
               $oldMenuId = $menu['id'] ;
               $menu['nav_id'] = $targetNavId ;
               unset($menu['id'] ,$menu['created_at'] ,$menu['updated_at']  );
               $navNewMenu = NavMenuEntity::storage($menu);
               $tempOldNewIdMap [$oldMenuId] = $navNewMenu ? $navNewMenu->id : 0;
           }

           //更新parent_id
           foreach ($tempOldNewIdMap as $oldId=>$newId){
               //更新
               NavMenuEntity::matchUpdate(['nav_id'=>$targetNavId , 'parent_id'=>$oldId ] , ['parent_id'=>$newId ]);

               //复制绑定
               $menuBinds = $this->bindAllInfo( $oldId );

               if( $menuBinds ){
                    foreach ($menuBinds as $index=> $bindItem){
                        unset( $menuBinds[$index]['id'] );
                        $menuBinds[$index]['menu_id'] = $newId;
                    }
                    //批量写入
                   MenuBindEntity::saveAll( $menuBinds );
               }
           }
           DB::commit();
           return true;
       }catch (\Exception $exception){
           DB::rollBack();
           return false;
       }
    }
}
